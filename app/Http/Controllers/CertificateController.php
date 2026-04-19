<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Http;
use App\Models\CertificateField;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    // 🧱 Builder UI
    public function builder($templateId)
    {
        $template = CertificateTemplate::with('fields')->findOrFail($templateId);
        return view('certificate.builder', compact('template'));
    }

    // 💾 Save drag positions
    public function savePosition(Request $request)
    {
        CertificateField::where('template_id', $request->template_id)
            ->where('field_name', $request->field_name)
            ->update([
                'x' => $request->x,
                'y' => $request->y,
            ]);

        return response()->json(['success' => true]);
    }

    // 📄 Generate PDF
    public function generate($templateId)
    {
 
        $template = CertificateTemplate::with('fields')->findOrFail($templateId);

        $data = [
            'name' => 'Nicko Y. Masangcay',
            'program' => 'Web Development Training',
            'date' => now()->format('F d, Y'),
        ];

        // Build HTML (same blade but WITHOUT dompdf restrictions)
        $html = view('certificate.pdf', compact('template', 'data'))->render();

        // Send to Node Puppeteer service
        $response = Http::post('http://127.0.0.1:3000/generate-pdf', [
            'html' => $html
        ]);

        return response($response->body(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="certificate.pdf"');
    }

    public function generateDOMPDF($templateId, $name, $date = null)
    {
        

        $template = CertificateTemplate::with('fields')->findOrFail($templateId);

            $data = [
            'name'    => $name,
            // 'program' => 'for successfully completing the 24-hour program, entitled 
            //     <br>

            //     <div style="
            //         display: block;
            //         width: 800px;
            //         margin: 0 auto;
            //         font-family: Poppins-Bold;
            //         font-size: 16px;
            //         font-weight: 700;
            //         text-align: center;
            //         white-space: normal;
            //         word-wrap: break-word;
            //         overflow-wrap: break-word;
            //         word-break: break-word;
            //     ">
            //         Promotion and Advocacy Marketing
            //     </div>  
            //     organized by the TESDA Development Institute (TDI) held from 01 to 03 December 2025
            //     <br>
            //     at the Regional TVET Innovation Center (RTIC), TESDA Complex,
            //     <br>
            //     Gate 2, East Service Road.
            //     <br><br>
            //     Given this 3rd day of December 2025 at TESDA Central Office, Taguig City.',
            'date'    => now()->format('F d, Y'),
        ];
        

        $html = view('certificate.pdf', compact('template', 'data'))->render();

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultFont', 'Poppins');
        $options->setChroot(public_path()); // ADD THIS

        $dompdf = new \Dompdf\Dompdf($options);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream("certificate.pdf", [
            "Attachment" => false
        ]);
    }
}
