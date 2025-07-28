<?php

namespace App\Http\Controllers;

use App\Services\QlsService;
use App\Services\PakbonService;
use iio\libmergepdf\Merger;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\PdfToImage\Pdf as PdfToImage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected QlsService $qlsService;
    protected PakbonService $pakbonService;

    public function __construct(QlsService $qlsService, PakbonService $pakbonService)
    {
        $this->qlsService = $qlsService;
        $this->pakbonService = $pakbonService;
    }

    public function index()
    {
        $shippingOptions = $this->qlsService->getShippingOptions()['data'] ?? [];

        $formData = [
            'order_number' => '958201',
            'customer_name' => 'John Doe',
            'street' => 'Daltonstraat',
            'house_number' => '65',
            'postal_code' => '3316GD',
            'city' => 'Dordrecht',
            'email' => 'email@example.com',
            'phone' => '010 1234567',
            'shipping_method' => 'dhl_standard',
        ];

        $items = [
            [
                'amount_ordered' => '2',
                'name' => 'Jeans, Black, 36',
                'sku' => '69205',
                'ean' => '8710552295268',
            ],
            [
                'amount_ordered' => '1',
                'name' => 'Sjaal, Rood Oranje',
                'sku' => '25920',
                'ean' => '3059943009097',
            ]
        ];

        return view('welcome', compact('formData', 'items', 'shippingOptions'));
    }

    public function store(Request $request)
    {
        // Step 1: Validate the request
        $validated = $request->validate([
            'order_number' => 'required|string',
            'customer_name' => 'required|string',
            'street' => 'required|string',
            'house_number' => 'required|string',
            'postal_code' => 'required|string',
            'city' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'shipping_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.amount_ordered' => 'required|integer|min:1',
            'items.*.name' => 'required|string',
            'items.*.sku' => 'required|string',
            'items.*.ean' => 'nullable|string',
        ]);

        // Step 2: Prepare shipment payload
        $shipmentProducts = collect($validated['items'])->map(fn($item) => [
            'amount' => $item['amount_ordered'],
            'name' => $item['name'],
            'sku' => $item['sku'],
            'ean' => $item['ean'] ?? null,
        ])->all();

        $contact = [
            'name' => $validated['customer_name'],
            'street' => $validated['street'],
            'housenumber' => $validated['house_number'],
            'postalcode' => $validated['postal_code'],
            'locality' => $validated['city'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => 'NL',
        ];

        $payload = [
            'product_combination_id' => 3,
            'brand_id' => 'e41c8d26-bdfd-4999-9086-e5939d67ae28',
            'customs_shipment_type' => 'commercial',
            'reference' => $validated['order_number'],
            'return_contact' => $contact,
            'sender_contact' => $contact,
            'receiver_contact' => $contact,
            'shipment_products' => $shipmentProducts,
        ];

        // Step 3: Create the shipment
        $shipment = $this->qlsService->createShipment($payload);

        // Step 4: Fetch and decode the label PDF
        $response = $this->qlsService->fetchLabel($shipment['data']['label_pdf_url']);
        $labelBase64 = json_decode($response->content(), true)['data'];
        $labelPdf = base64_decode($labelBase64);

        // Step 5: Convert label PDF to image
        $tempPdfPath = storage_path('app/label_' . Str::random(6) . '.pdf');
        file_put_contents($tempPdfPath, $labelPdf);

        $pdf = new PdfToImage($tempPdfPath);
        $tempImagePath = storage_path('app/label_img_' . Str::random(6) . '.png');
        $pdf->saveImage($tempImagePath);

        $labelImageBase64 = base64_encode(file_get_contents($tempImagePath));

        // Clean up temp files
        @unlink($tempPdfPath);
        @unlink($tempImagePath);

        // Step 6: Generate the PDF with the embedded label image
        $pakbonPdf = Pdf::loadView('pakbon.template', [
            'order_number' => $validated['order_number'],
            'date' => Carbon::now()->format('d-m-Y'),
            'customer' => [
                'name' => $validated['customer_name'],
                'street' => $validated['street'],
                'house_number' => $validated['house_number'],
                'postal_code' => $validated['postal_code'],
                'city' => $validated['city'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ],
            'items' => $validated['items'],
            'label_image' => $labelImageBase64,
        ])->output();

        // Step 7: Return the final PDF
        return response($pakbonPdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="pakbon_met_label.pdf"',
        ]);
    }
}
