<?php

use Illuminate\Support\Facades\Validator;
use App\Author;
class BooksController extends Controller
{
public function importExcel(Request $request)
{

// validasi untuk memastikan file yang diupload adalah excel
$this->validate($request, [ 'excel' => 'required|mimes:xlsx' ]);
// ambil file yang baru diupload
$excel = $request->file('excel');
// baca sheet pertama
$excels = Excel::selectSheetsByIndex(0)->load($excel, function($reader) {
// options, jika ada
})->get();
// rule untuk validasi setiap row pada file excel
$rowRules = [
];
'judul' => 'required',
'penulis' => 'required',
'jumlah' => 'required'
// Catat semua id buku baru
// ID ini kita butuhkan untuk menghitung total buku yang berhasil diimport
$books_id = [];
// looping setiap baris, mulai dari baris ke 2 (karena baris ke 1 adalah nama kolom)
foreach ($excels as $row) {
}
// Membuat validasi untuk row di excel
// Disini kita ubah baris yang sedang di proses menjadi array
$validator = Validator::make($row->toArray(), $rowRules);
// Skip baris ini jika tidak valid, langsung ke baris selanjutnya
if ($validator->fails()) continue;
// Syntax dibawah dieksekusi jika baris excel ini valid
// Cek apakah Penulis sudah terdaftar di database
$author = Author::where('name', $row['penulis'])->first();
// buat penulis jika belum ada
if (!$author) {
$author = Author::create(['name'=>$row['penulis']]);
}
// buat buku baru
$book = Book::create([
'title' => $row['judul'],
'author_id' => $author->id,
'amount' => $row['jumlah']
]);
// catat id dari buku yang baru dibuat
array_push($books_id, $book->id);
// Ambil semua buku yang baru dibuat
$books = Book::whereIn('id', $books_id)->get();
// redirect ke form jika tidak ada buku yang berhasil diimport
