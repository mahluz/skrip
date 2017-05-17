<style type="text/css">
    .page_header {
    	border: none; 
    	background-image: url({{ asset('dist/img/header.png') }}); 
    	background-size: 100% 100%;
    	height:130px;
    	padding: 2mm 
    }
    .page_footer {
    	border: none; 
    	background-image: url({{ asset('dist/img/footer.png') }}); 
    	background-size: 100% 100%;
    	height : 130px;
    	padding: 2mm
    }
    h1 {color: #000033}
    h2 {color: #000055}
    h3 {color: #000077}
</style>
<page backtop="40mm" backbottom="40mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
    <page_header>
        <div class="page_header"></div>
    </page_header>
    <page_footer>
        <div class="page_footer"></div>
    </page_footer>
	<table cellpadding="100" cellspacing="20">
		<tr>
		  <th colspan=2>Penulis</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ ucwords($makalah->name) }}</td>
		</tr>
		<tr>
		  <th colspan=2>Nomor Pegawai</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->no_id }}</td>
		</tr>
		<tr>
		  <th colspan=2>Judul</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ ucfirst($makalah->judul) }}</td>
		</tr>
		<tr>
		  <th colspan=2>Abstrak</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->abstrak }}</td>
		</tr>
		<tr>
		  <th colspan=2>Permasalahan</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->permasalahan }}</td>
		</tr>
		<tr>
		  <th colspan=2>Tujuan</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->tujuan }}</td>
		</tr>
		<tr>
		  <th colspan=2>Tinjauan pustaka</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->tinjauan_pustaka }}</td>
		</tr>
		<tr>
		  <th colspan=2>Kesimpulan Sementara</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->kesimpulan_sementara }}</td>
		</tr>
		<tr>
		  <th colspan=2>Daftar Pustaka</th>
		</tr>
		<tr>
		  <td></td>
		  <td>{{ $makalah->daftar_pustaka }}</td>
		</tr>
	  </table>
</page>