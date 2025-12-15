<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateSurat; // Pastikan model ini ada
use App\Models\JenisSurat;

class TemplateController extends Controller
{
    // Menampilkan daftar template (template.php)
    public function index()
    {
        // Menggunakan model TemplateSurat sesuai kode Anda, asumsikan relasi sudah benar
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