<?php 
	$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];
?>

<table width="100%">
	<thead>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="font-weight: 800">Laporan Neraca Saldo <small>(x1000)</small></td>
		</tr>

		<tr>
			<td></td>
			<td>{{ jurnal()->companyName }}</td>
		</tr>

		<tr>
			<td></td>
			<td style="border-bottom: 1px solid #ccc; padding-bottom: 20px;"><small>Bulan {{ $tanggal_1 }}</small></td>
		</tr>
	</thead>
</table>

<br>

	<table width="100%" style="font-size: 9pt;">
		<tbody>
			<tr>
				<td></td>
				<td rowspan="2" style="background-color: #0099cc; color: #ffffff;">Kode Akun</td>
				<td rowspan="2" style="background-color: #0099cc; color: #ffffff;">Saldo Awal</td>

				<td colspan="2" style="background-color: #0099cc; color: #ffffff;">Mutasi Kas</td>
				<td colspan="2" style="background-color: #0099cc; color: #ffffff;">Mutasi Bank</td>
				<td colspan="2" style="background-color: #0099cc; color: #ffffff;">Mutasi Memorial</td>
				<td colspan="2" style="background-color: #0099cc; color: #ffffff;">Total Mutasi</td>
				<td rowspan="2" style="background-color: #0099cc; color: #ffffff;">Saldo Akhir</td>
			</tr>

			<tr>
				<td></td>
				<td style="background-color: #0099cc; color: #ffffff;">Debet</td>
				<td style="background-color: #0099cc; color: #ffffff;">Kredit</td>

				<td style="background-color: #0099cc; color: #ffffff;">Debet</td>
				<td style="background-color: #0099cc; color: #ffffff;">Kredit</td>

				<td style="background-color: #0099cc; color: #ffffff;">Debet</td>
				<td style="background-color: #0099cc; color: #ffffff;">Kredit</td>

				<td style="background-color: #0099cc; color: #ffffff;">Debet</td>
				<td style="background-color: #0099cc; color: #ffffff;">Kredit</td>
			</tr>

				<?php
					$kd = $kk = $bd = $bk = $md = $mk = $td = $tk = 0;
				?>

			@foreach($data['data'] as $key => $akun)
				<tr>
					<td></td>
					<td>{{ $akun->ak_id }}</td>
					<td>
						{{ $akun->saldo_awal }}
					</td>

					<td> {{ $akun->kas_debet }}</td>
					<td> {{ $akun->kas_kredit }}</td>

					<td> {{ $akun->bank_debet }}</td>
					<td> {{ $akun->bank_kredit }}</td>

					<td> {{ $akun->memorial_debet }}</td>
					<td> {{ $akun->memorial_kredit }}</td>


					<td>
						{{ ($akun->kas_debet + $akun->bank_debet + $akun->memorial_debet) }}
					</td>
					<td>
						{{ ($akun->kas_kredit + $akun->bank_kredit + $akun->memorial_kredit) }}
					</td>

					<td>
						{{ $akun->saldo_akhir }}
					</td>
				</tr>

				<?php
					$kd += $akun->kas_debet;
					$kk += $akun->kas_kredit;

					$bd += $akun->bank_debet;
					$bk += $akun->bank_kredit;

					$md += $akun->memorial_debet;
					$mk += $akun->memorial_kredit;

					$td += ($akun->kas_debet + $akun->bank_debet + $akun->memorial_debet);
					$tk += ($akun->kas_kredit + $akun->bank_kredit + $akun->memorial_kredit);
				?>

			@endforeach

			<tr>
				<td></td>
				<td></td>
				<td>
					
				</td>

				<td style="background-color: #0099cc; color: #ffffff;"> {{ $kd }}</td>
				<td style="background-color: #0099cc; color: #ffffff;"> {{ $kk }}</td>

				<td style="background-color: #0099cc; color: #ffffff;"> {{ $bd }}</td>
				<td style="background-color: #0099cc; color: #ffffff;"> {{ $bk }}</td>

				<td style="background-color: #0099cc; color: #ffffff;"> {{ $md }}</td>
				<td style="background-color: #0099cc; color: #ffffff;"> {{ $mk }}</td>


				<td style="background-color: #0099cc; color: #ffffff;">
					{{ $td }}
				</td>
				<td style="background-color: #0099cc; color: #ffffff;">
					{{ $tk }}
				</td>

				<td>
					
				</td>
			</tr>
			
		</tbody>
	</table>