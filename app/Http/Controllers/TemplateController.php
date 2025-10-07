<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateSurat;
use App\Models\JenisSurat;

class TemplateController extends Controller
{
    // Menampilkan daftar template (template.php)
    public function index()
    {
        $templates = TemplateSurat::with('jenisSurat')->get();
        return view('mahasiswa.template', compact('templates'));
    }

    // Mengambil link template via AJAX (template_ajax.php)
    public function getLink(Request $request)
    {
        $template = TemplateSurat::where('jenis_surat_id', $request->jenis_id)->first();
        return response()->json(['link' => $template ? $template->file_link : null]);
    }
}