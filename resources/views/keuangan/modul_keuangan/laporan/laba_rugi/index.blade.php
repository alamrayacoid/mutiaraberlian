<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laporan Laba Rugi</title>
        
		<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/bootstrap_4_1_3/css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/font-awesome_4_7_0/css/font-awesome.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/css/style.css') }}">
  		<link rel="stylesheet" type="text/css" href="{{asset('modul_keuangan/js/vendors/ez_popup_v_1_1/ez.popup.css')}}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/select2/dist/css/select2.min.css') }}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/datepicker/dist/datepicker.min.css') }}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.css') }}">

		<style>

			body{
				background: rgba(0,0,0, 0.5);
			}

			/*.bs-datepicker-container { z-index: 3000; }*/

			.lds-dual-ring {
			  display: inline-block;
			  width: 64px;
			  height: 64px;
			}
			.lds-dual-ring:after {
			  content: " ";
			  display: block;
			  width: 46px;
			  height: 46px;
			  margin: 1px;
			  border-radius: 50%;
			  border: 5px solid #dfc;
			  border-color: #dfc transparent #dfc transparent;
			  animation: lds-dual-ring 1.2s linear infinite;
			}
			@keyframes lds-dual-ring {
			  0% {
			    transform: rotate(0deg);
			  }
			  100% {
			    transform: rotate(360deg);
			  }
			}

		    .navbar-brand {
		    	padding-left: 30px;
		    }

		    .navbar-nav {
		      flex-direction: row;
		      padding-right: 40px; 
		    }
		    
		    .nav-link {
		      padding-right: .5rem !important;
		      padding-left: .5rem !important;
		    }
		    
		    /* Fixes dropdown menus placed on the right side */
		    .ml-auto .dropdown-menu {
		      left: auto !important;
		      right: 0px;
		    }

		    .nav-item{
		    	color: white;
		    }

		    .navbar-nav li{
		        border-left: 1px solid rgba(255, 255, 255, 0.1);
		        padding: 0px 25px;
		        cursor: pointer;
		    }

		    .navbar-nav li:last-child{
		    	border-right: 1px solid rgba(255, 255, 255, 0.1);
		    }

		    .ctn-nav {
		    	background: rgba(0,0,0, 0.7);
		    	position: fixed;
		    	bottom: 1.5em;
		    	z-index: 1000;
		    	font-size: 10pt;
		    	box-shadow: 0px 0px 10px #aaa;
		    	border-radius: 10px
		    }

		    #title-table{
		    	padding: 0px;
		    }

		    #table-data{
		    	font-size: 9pt;
		    }

		    #table-data td, #table-data th {
		    	padding: 5px 10px;
		    	border: 0px solid #eee;
		    }

		    #table-data td.head{
		    	border: 1px solid white;
		    	background: #0099CC;
		    	color: white;
		    	font-weight: bold;
		    	text-align: center;
		    }

		    #table-data td.sub-head{
		    	border: 1px solid #0099CC;
		    	color: #333;
		    	font-weight: bold;
		    	text-align: center;
		    }

		    #contentnya{
	          width: 65%;
	          padding: 0px 20px;
	          background: white;
	          min-height: 700px;
	          border-radius: 2px;
	          margin: 0 auto;
	        }

		</style>


		<style type="text/css" media="print">
          @page { size: portrait; }
          nav{
            display: none;
          }

          .ctn-nav{
            display: none;
          }

          #contentnya{
          	width: 100%;
          	padding: 0px;
          	margin-top: -80px;
          }

          #table-data th{
             background-color: #0099CC !important;
             color: white;
             -webkit-print-color-adjust: exact;
          }

          #table-data td.not-same{
             color: red !important;
             -webkit-print-color-adjust: exact;
          }

          .page-break { display: block; page-break-before: always; }
      	</style>
	</head>

	<body>
		<div id="vue-element">
			<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="box-shadow: 0px 5px 10px #555;">
			    <a class="navbar-brand" href="{{ url('/') }}">{{ jurnal()->companyName }}</a>

			    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			      <span class="navbar-toggler-icon"></span>
			    </button>

			    <div class="collapse navbar-collapse" id="navbarCollapse">
			      <ul class="navbar-nav ml-auto">

			      	<li class="nav-item">
			      	  <a href="{{ route('laporan.keuangan.index') }}" style="color: #ffbb33;">
			          	<i class="fa fa-backward" title="Kembali Ke Menu Laporan"></i>
			          </a>
			        </li>

			        <li class="nav-item">
			          	<i class="fa fa-print" title="Print Laporan" @click="print"></i>
			        </li>

			        <li class="nav-item dropdown" title="Download Laporan">
			          	<i class="fa fa-download" id="dropdownMenuButton" data-toggle="dropdown"></i>

			            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="#" style="font-size: 10pt;" @click='downloadPdf'>
						    	<i class="fa fa-file-pdf-o" style="font-weight: bold;"></i> &nbsp; Download PDF
						    </a>

						    <div class="dropdown-divider"></div>

						    <a class="dropdown-item" href="#" style="font-size: 10pt;" @click='downloadExcel'>
						    	<i class="fa fa-file-excel-o" style="font-weight: bold;"></i> &nbsp; Download Excel
						    </a>
					    </div>
			        </li>

			        <li class="nav-item">
			          <i class="fa fa-sliders" title="Pengaturan Laporan" @click="showSetting"></i>
			        </li>

			      </ul>
			    </div>
			</nav>

			{{-- <div class="col-md-4 offset-4 ctn-nav" v-cloak>
				<div class="row" style="color: white; padding: 8px 0px;">
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td class="text-center" width="40%" style="border-left: 0px solid #999; font-style: italic;">Menampilkan Halaman</td>
								<td class="text-center" width="10%" style="border-left: 1px solid #999;">@{{ pageNow }}</td>
								<td class="text-center" width="10%" style="border-left: 1px solid #999;">
									/
								</td>
								<td class="text-center" width="10%" style="border-left: 1px solid #999;">@{{ dataPage }}</td>
								<td class="text-center" width="15%" style="border-left: 1px solid #999;">
									<i class="fa fa-arrow-left" :style="(!previousDisabled) ? 'cursor: pointer; color: #fff' : 'cursor: no-drop; color: #888'" @click="previousPage"></i>
								</td>
								<td class="text-center" width="15%">
									<i class="fa fa-arrow-right" :style="(!nextDisabled) ? 'cursor: pointer; color: #fff' : 'cursor: no-drop; color: #888'" @click="nextPage"></i>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div> --}}

			<div class="container-fluid" style="background: none; margin-top: 70px; padding: 10px 30px;">
				<div id="contentnya">

					<?php 
						if($_GET['type'] == 'bulan')
							$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];
						else
							$tanggal_1 = $_GET['y1'];
					?>					

					{{-- Judul Kop --}}

						<table width="100%" border="0" style="border-bottom: 1px solid #333;" v-if="pageNow == 1" v-cloak>
				          <thead>
				            <tr>
				              <th style="text-align: left; font-size: 14pt; font-weight: 600; padding-top: 10px;" colspan="2">Laporan Laba Rugi</th>
				            </tr>

				            <tr>
				              <th style="text-align: left; font-size: 12pt; font-weight: 500" colspan="2">{{ jurnal()->companyName }}</th>
				            </tr>

				            <tr>
				              <th style="text-align: left; font-size: 8pt; font-weight: 500; padding-bottom: 10px;">(Angka Disajikan Dalam Rupiah, Kecuali Dinyatakan Lain)</th>

				              <th class="text-right" style="font-size: 8pt; font-weight: normal;">
				              	<b>Periode {{ $tanggal_1 }}</b>
				              </th>
				            </tr>
				          </thead>
				        </table>

				    {{-- End Judul Kop --}}

			    	<div style="padding-top: 20px;">
						<table class="table" id="table-data" v-cloak>
							<tbody>
								<tr>
									<td>
										<table width="100%" style="font-size: 10pt;">
											<table class="table" id="table-data" v-cloak>
												<tbody>
													<template v-for="(data, dtx) in dataPrint" v-if="data.hls_id == 4 || data.hls_id == 5">
													<tr>
														<td style="border: 0px; font-weight: bold;">@{{ data.hls_nama }}</td>
														<td style="border: 0px; text-align: right; font-size: 10pt;">
															
														</td>
													</tr>

													<template v-for="(subclass, idx) in data.subclass">
														<tr v-if="subclass.hs_nama != 'Tidak Memiliki'">
															<td style="border: 0px; font-weight: 500; padding-left: 25px; font-style: italic;">@{{ subclass.hs_nama }}</td>
															<td style="border: 0px; text-align: right; font-size: 10pt;">
																
															</td>
														</tr>

														<template v-for="(level2, alpha) in subclass.level_2">
															<tr>
																<td :style="(subclass.hs_nama != 'Tidak Memiliki') ? 'border: 0px; font-weight: 600; padding-left: 50px; font-style: normal;' : 'border: 0px; font-weight: 600; padding-left: 25px; font-style: normal;'">@{{ level2.hld_id+' - '+level2.hld_nama }}</td>
																<td style="border: 0px; text-align: right; font-size: 10pt;">
																	@{{ (detail.level2['_'+level2.hld_id] < 0) ? '('+humanizePrice(detail.level2['_'+level2.hld_id])+')' : humanizePrice(detail.level2['_'+level2.hld_id]) }}
																</td>
															</tr>
														</template>

															{{-- <tr v-if="subclass.hs_nama != 'Tidak Memiliki'">
																<td style="border: 0px; font-weight: 600; padding-left: 25px; font-style: normal;">Total @{{ subclass.hs_nama }}aa</td>
																<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #eee; font-weight: 600; ">
																	@{{ (detail.subclass['_'+subclass.hs_id] < 0) ? '('+humanizePrice(detail.subclass['_'+subclass.hs_id])+')' : humanizePrice(detail.subclass['_'+subclass.hs_id])}}
																</td>
															</tr> --}}

													</template>

													<tr>
														<td style="border: 0px; font-weight: bold; text-align: left;">Total @{{ data.hls_nama }}</td>
														
														<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc; font-weight: bold;">
															@{{ (detail.level1['_'+data.hls_id] < 0) ? '('+humanizePrice(detail.level1['_'+data.hls_id])+')' : humanizePrice(detail.level1['_'+data.hls_id])}}
														</td>
													</tr>

													<tr><td colspan="2" style="border: 0px; padding: 0px;">&nbsp;</td></tr>

												</template>

												<tr>
													<td style="border: 0px; font-weight: bold; text-align: left; color: #0099CC; font-style: italic; padding-left: 30px;">Total Laba Rugi Kotor</td>
													
													<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc; color: #0099CC; font-weight: bold;">
														@{{ (parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5']) < 0) ? '('+humanizePrice(parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5']))+')' : humanizePrice(parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) }}
													</td>
												</tr>

												<tr><td colspan="2" style="border: 0px; padding: 0px;">&nbsp;</td></tr>

												<template v-for="(data, dtx) in dataPrint" v-if="data.hls_id == 6 || data.hls_id == 7">
													<tr>
														<td style="border: 0px; font-weight: bold;">@{{ data.hls_nama }}</td>
														<td style="border: 0px; text-align: right; font-size: 10pt;">
															
														</td>
													</tr>

													<template v-for="(subclass, idx) in data.subclass">
														<tr v-if="subclass.hs_nama != 'Tidak Memiliki'">
															<td style="border: 0px; font-weight: 500; padding-left: 25px; font-style: italic;">@{{ subclass.hs_nama }}</td>
															<td style="border: 0px; text-align: right; font-size: 10pt;">
																
															</td>
														</tr>

														<template v-for="(level2, alpha) in subclass.level_2">
															<tr>
																<td :style="(subclass.hs_nama != 'Tidak Memiliki') ? 'border: 0px; font-weight: 600; padding-left: 50px; font-style: normal;' : 'border: 0px; font-weight: 600; padding-left: 25px; font-style: normal;'">@{{ level2.hld_id+' - '+level2.hld_nama }}</td>
																<td style="border: 0px; text-align: right; font-size: 10pt;">
																	@{{ (detail.level2['_'+level2.hld_id] < 0) ? '('+humanizePrice(detail.level2['_'+level2.hld_id])+')' : humanizePrice(detail.level2['_'+level2.hld_id]) }}
																</td>
															</tr>
														</template>

															{{-- <tr v-if="subclass.hs_nama != 'Tidak Memiliki'">
																<td style="border: 0px; font-weight: 600; padding-left: 25px; font-style: normal;">Total @{{ subclass.hs_nama }}</td>
																<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #eee; font-weight: 600; ">
																	@{{ (detail.subclass['_'+subclass.hs_id] < 0) ? '('+humanizePrice(detail.subclass['_'+subclass.hs_id])+')' : humanizePrice(detail.subclass['_'+subclass.hs_id])}}
																</td>
															</tr> --}}

													</template>

													<tr>
														<td style="border: 0px; font-weight: bold; text-align: left;">Total @{{ data.hls_nama }}</td>
														
														<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc; font-weight: bold">
															@{{ (detail.level1['_'+data.hls_id] < 0) ? '('+humanizePrice(detail.level1['_'+data.hls_id])+')' : humanizePrice(detail.level1['_'+data.hls_id])}}
														</td>
													</tr>

													<tr><td colspan="2" style="border: 0px; padding: 0px;">&nbsp;</td></tr>

												</template>

												<tr>
													<td style="border: 0px; font-weight: bold; text-align: left; color: #0099CC; font-style: italic; padding-left: 30px;">Total Beban Operasi & Administrasi Umum</td>
													
													<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc;  color: #0099CC; font-weight: bold;">
														@{{ humanizePrice(parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7'])) }}
													</td>
												</tr>

												<tr>
													<td style="border: 0px; font-weight: bold; text-align: left; color: #0099CC; font-style: italic; padding-left: 30px;">Laba Operasi</td>
													
													<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc; color: #0099CC; font-weight: bold;">
														@{{ ((parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) - (parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7'])) < 0)? '('+humanizePrice((parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) - (parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7'])))+')' : humanizePrice((parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) - (parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7']))) }}
													</td>
												</tr>

												<tr><td colspan="2" style="border: 0px; padding: 0px;">&nbsp;</td></tr>

												<template v-for="(data, dtx) in dataPrint" v-if="data.hls_id == 8 || data.hls_id == 9">
													<tr>
														<td style="border: 0px; font-weight: bold;">@{{ data.hls_nama }}</td>
														<td style="border: 0px; text-align: right; font-size: 10pt;">
															
														</td>
													</tr>

													<template v-for="(subclass, idx) in data.subclass">
														<tr v-if="subclass.hs_nama != 'Tidak Memiliki'">
															<td style="border: 0px; font-weight: 500; padding-left: 25px; font-style: italic;">@{{ subclass.hs_nama }}</td>
															<td style="border: 0px; text-align: right; font-size: 10pt;">
																
															</td>
														</tr>

														<template v-for="(level2, alpha) in subclass.level_2">
															<tr>
																<td :style="(subclass.hs_nama != 'Tidak Memiliki') ? 'border: 0px; font-weight: 600; padding-left: 50px; font-style: normal;' : 'border: 0px; font-weight: 600; padding-left: 25px; font-style: normal;'">@{{ level2.hld_id+' - '+level2.hld_nama }}</td>
																<td style="border: 0px; text-align: right; font-size: 10pt;">
																	@{{ (detail.level2['_'+level2.hld_id] < 0) ? '('+humanizePrice(detail.level2['_'+level2.hld_id])+')' : humanizePrice(detail.level2['_'+level2.hld_id]) }}
																</td>
															</tr>
														</template>

															{{-- <tr v-if="subclass.hs_nama != 'Tidak Memiliki'">
																<td style="border: 0px; font-weight: 600; padding-left: 25px; font-style: normal;">Total @{{ subclass.hs_nama }}</td>
																<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #eee; font-weight: 600; ">
																	@{{ (detail.subclass['_'+subclass.hs_id] < 0) ? '('+humanizePrice(detail.subclass['_'+subclass.hs_id])+')' : humanizePrice(detail.subclass['_'+subclass.hs_id])}}
																</td>
															</tr> --}}

													</template>

													<tr>
														<td style="border: 0px; font-weight: bold; text-align: left;">Total @{{ data.hls_nama }}</td>
														
														<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc; font-weight: bold;">
															@{{ (detail.level1['_'+data.hls_id] < 0) ? '('+humanizePrice(detail.level1['_'+data.hls_id])+')' : humanizePrice(detail.level1['_'+data.hls_id])}}
														</td>
													</tr>

													<tr><td colspan="2" style="border: 0px; padding: 0px;">&nbsp;</td></tr>

												</template>

												<tr>
													<td style="border: 0px; font-weight: bold; text-align: left; color: #0099CC; font-style: italic; padding-left: 0px;">Laba Sebelum Pajak</td>
													
													<td style="border: 0px; text-align: right; font-size: 10pt; border-top: 1px solid #ccc;  color: #0099CC; font-weight: bold;">
														@{{ ((parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) - (parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7'])) + (parseFloat(detail.level1['_8'] - parseFloat(detail.level1['_9']))) < 0) ? '('+humanizePrice((parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) - (parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7'])) + (parseFloat(detail.level1['_8'] - parseFloat(detail.level1['_9']))))+')' : humanizePrice((parseFloat(detail.level1['_4']) - parseFloat(detail.level1['_5'])) - (parseFloat(detail.level1['_6']) + parseFloat(detail.level1['_7'])) + (parseFloat(detail.level1['_8'] - parseFloat(detail.level1['_9'])))) }}
													</td>
												</tr>
												</tbody>
											</table>
										</table>	
									</td>
								</tr>

								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="ez-popup" id="loading-popup">
	            <div class="layout text-center" style="width: 50%; background: none; box-shadow: none; color: white; min-height: 0px; margin-top: 250px; border-radius: 2px;">
	                   <span style="font-size: 11pt; font-style: italic;">
	                   		<div class="lds-dual-ring" v-if="textLoading == 'Sedang Menyiapkan Laporan . Harap Tunggu...'"></div>
	                   		<i class="fa fa-frown-o" style="font-size: 42pt; margin-bottom: 20px;" v-if="textLoading != 'Sedang Menyiapkan Laporan . Harap Tunggu...'"></i>
	                   		<br>
	                   		@{{ textLoading }}
	                   	</span>
	            </div>
	        </div>

	        <div class="ez-popup" id="setting-popup">
	            <div class="layout" style="width: 35%; min-height: 150px;">
	                <div class="top-popup" style="background: none;">
	                    <span class="title">
	                        Setting Laporan Laba Rugi
	                    </span>

	                    <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
	                </div>
	                
	                <div class="content-popup">
	                	<form id="form-setting" method="get" action="{{ route('laporan.keuangan.laba_rugi') }}">
	                	<input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
	                    <div class="col-md-12">

	                    	<div class="row mt-form">
	                            <div class="col-md-4">
	                                <label class="modul-keuangan">Type Laba Rugi</label>
	                            </div>

	                            <div class="col-md-7">
	                            	<vue-select :name="'type'" :id="'type'" :options="typeLaporan" :styles="'width:100%'" @input="typeChange"></vue-select>
	                            </div>

	                        </div>

	                        <div class="row mt-form">
	                            <div class="col-md-4">
	                                <label class="modul-keuangan">Periode</label>
	                            </div>

	                            <div class="col-md-7" v-show="type == 'bulan'">
                    				<vue-datepicker :name="'d1'" :id="'d1'" :title="'Tidak Boleh Kosong'" :readonly="true" :placeholder="'Pilih Tanggal'" :format="'mm/yyyy'" :styles="'font-size: 9pt;'"></vue-datepicker>
	                            </div>

	                            <div class="col-md-7" v-show="type == 'tahun'">
                    				<vue-datepicker :name="'y1'" :id="'y1'" :title="'Tidak Boleh Kosong'" :readonly="true" :placeholder="'Pilih Tanggal'" :format="'yyyy'" :styles="'font-size: 9pt;'"></vue-datepicker>
	                            </div>
	                        </div>

	                    </div>

	                    <div class="col-md-12" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
	                    	<div class="row">
		                    	<div class="col-md-8" style="padding: 0px; padding-top: 5px; padding-left: 10px; color: #666;">
	                                <div class="loader" v-if="stat == 'loading'" v-cloak>
	                                   <div class="loading"></div> &nbsp; <span>@{{ statMessage }}</span>
	                                </div>
	                            </div>

		                    	<div class="col-md-4 text-right" style="padding: 0px;">
		                    		<button type="button" class="btn btn-info btn-sm" @click='prosesLaporan'>Proses</button>
		                    	</div>
		                    </div>
	                    </div>

	                    </form>
	                </div>
	            </div>
	        </div>

	        <iframe style="display: none;" id='pdfIframe' src=''/></iframe>
		</div>

		<script src="{{ asset('modul_keuangan/js/jquery_3_3_1.min.js') }}"></script>
		<script src="{{ asset('modul_keuangan/bootstrap_4_1_3/js/bootstrap.min.js') }}"></script>
		<script src="{{asset('modul_keuangan/js/vendors/ez_popup_v_1_1/ez.popup.js')}}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/axios_0_18_0/axios.min.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/select2/dist/js/select2.min.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/datepicker/dist/datepicker.min.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.js') }}"></script>

    	<script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/vue_2_x.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/components/select.component.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/vue_2_x/components/datepicker.component.js') }}"></script>

    	<script type="text/javascript">

			var app = 	new Vue({
			    			el: '#vue-element',
			    			data: {

			    				textLoading: "",
			    				statMessage: "Sedang Menyiapkan Laporan..",
			    				stat: "standby",
			    				url: new URL(window.location.href),

			    				firstElement: 0,
			    				dataPage: 1,
			    				pageNow: 0,
			    				rowsCount: 50,

			    				nextDisabled: false,
			    				previousDisabled: true,

			    				dataSource: [],
			    				dataPrint: [],
			    				grandAktiva: 0,
			    				grandPasiva: 0,

			    				// setting
			    					type: 'bulan',
			    					kelompok: [],
			    					typeLaporan: [
				    					{
				    						id: 'bulan',
				    						text: 'Laporan Laba Rugi Bulanan',
				    					}
				    				],

				    				tampilan: [
				    					{
				    						id: 'tabular',
				    						text: 'Tampilan Table',
				    					},

				    					{
				    						id: 'menurun',
				    						text: 'Tampilan Menurun'
				    					}
				    				],
			    			},

			    			created: function(){
				                console.log('Initializing Vue');
				            },

				            mounted: function(){
				            	console.log('Vue Ready');
				            	this.textLoading = "Sedang Menyiapkan Laporan . Harap Tunggu...";
				            	$('#loading-popup').ezPopup('show');

				            	$('#d1').val('{{ $_GET['d1'] }}');
				            	$('#y1').val('{{ $_GET['y1'] }}');
				            	$('#type').val('{{ $_GET['type'] }}').trigger('change.select2');
				            	this.typeChange('{{ $_GET['type'] }}');

				            	that = this;

				            	axios.get('{{route('laporan.keuangan.laba_rugi.data_resource')}}?'+that.url.searchParams)
			                            .then((response) => {

			                                if(response.data.data.length){
			                                	this.dataSource = response.data.data;
			                                	this.pageNow = 1;

			                                	if(this.dataSource.length / this.rowsCount < 1){
			                                		this.dataPage = Math.floor(this.dataSource.length / this.rowsCount) + 1;
			                                		// alert('a')
			                                	}else if((this.dataSource.length / this.rowsCount) % 1 == 0){
			                                		this.dataPage = Math.floor(this.dataSource.length / this.rowsCount);
			                                		// alert('b')
			                                	}else if(this.dataSource.length / this.rowsCount > 1){
			                                		this.dataPage = Math.floor(this.dataSource.length / this.rowsCount) + 1;
			                                		// alert('c')
			                                	}

			                                	if(this.pageNow == this.dataPage)
			                                		this.nextDisabled = true;

			                                }else{
			                                	this.pageNow = 1;
			                                }

			                                // if(response.data.kelompok.length){
			                                // 	this.kelompok = response.data.kelompok;
			                                // }

			                                $('#loading-popup').ezPopup('close');
			                            })
			                            .catch((e) => {
			                            	this.textLoading = "Data Laporan Bermasalah. Segera Hubungi Developer. message : "+e;
			                            })
				            },

				            computed: {
				            	detail: function(){
				            		that = this;
				            		var clock = []; grandAktiva = grandPasiva = 0; $a = 0;
				            		var totSubclass = {}; var totLevel2 = {}; var totLevel1 = {};
				            		var bucket = {};

				            		$.each(this.dataPrint, function(idx1, level_1){
				            			var level1 = 0;

				            			$.each(level_1.subclass, function(idx2, subclass){
				            				var subClass = 0;

				            				$.each(subclass.level_2, function(idx3, level_2){
				            					var level2 = 0;

				            					$.each(level_2.akun, function(idx4, akun){
				            						level2 += parseFloat(akun.saldo_akhir);
				            					})

				            					subClass += parseFloat(level2);
				            					totLevel2['_'+level_2.hld_id] = level2;
				            				})

				            				level1 += parseFloat(subClass);
				            				totSubclass['_'+subclass.hs_id] = subClass;
				            			})

				            			totLevel1['_'+level_1.hls_id] = level1;

				            			if(level_1.hls_id == 1)
				            				grandAktiva += level1;
				            			else
				            				grandPasiva += level1;
				            		})

				            		bucket = {
				            			level1 : totLevel1,
				            			subclass : totSubclass,
				            			level2 : totLevel2,
				            			grandAktiva : grandAktiva,
				            			grandPasiva : grandPasiva
				            		}

				                	console.log(totSubclass);
				                	return bucket;
				            	}
				            },

				            watch: {
				            	pageNow: function(){
				            		var dump = []; var c = [];

				            		for (i = this.firstElement; i < (this.firstElement + this.rowsCount); i++){
				            			if(i < this.dataSource.length){
				            				dump.push(this.dataSource[i]);
				            				c.push(i);
				            			}else{
				            				break;
				            			}
				            		}

				            		this.dataPrint = dump;
				            		console.log(this.dataPrint);
				            	}
				            },

				            methods: {
				            	previousPage: function(){
				            		if(this.pageNow > 1){
				            			this.pageNow--;
				            			this.firstElement -= (this.rowsCount);

				            			this.previousDisabled = (this.pageNow == 1) ? true : false;
				            			this.nextDisabled = (this.pageNow == this.dataPage) ? true : false;
				            		}
				            	},

				            	nextPage: function(){
				            		if(this.pageNow < this.dataPage){
				            			this.pageNow++;
				            			this.firstElement += (this.rowsCount);

				            			this.nextDisabled = (this.pageNow == this.dataPage) ? true : false;
				            			this.previousDisabled = (this.pageNow == 1) ? true : false;
				            		}
				            	},

				            	showSetting: function(evt){
				            		evt.preventDefault();
				                	evt.stopImmediatePropagation();

				                	$('#setting-popup').ezPopup('show');
				            	},

				            	downloadPdf: function(evt){
				            		evt.preventDefault();
				                	evt.stopImmediatePropagation();

				                	$.toast({
									    text: "Sedang Mendownload Laporan PDF",
			                            showHideTransition: 'slide',
			                            position: 'bottom-right',
			                            icon: 'info',
			                            hideAfter: 10000,
			                            showHideTransition: 'slide',
			                            allowToastClose: false,
			                            stack: false
									});

				                    $('#pdfIframe').attr('src', '{{route('laporan.keuangan.laba_rugi.print.pdf')}}?'+that.url.searchParams)

				            	},

				            	downloadExcel: function(evt){
				            		evt.preventDefault();
				                	evt.stopImmediatePropagation();

				                	$.toast({
			                            text: "Sedang Mendownload Laporan EXCEL",
			                            showHideTransition: 'slide',
			                            position: 'bottom-right',
			                            icon: 'info',
			                            hideAfter: 10000,
			                            showHideTransition: 'slide',
			                            allowToastClose: false,
			                            stack: false
			                        });

			                        $('#pdfIframe').attr('src', '{{route('laporan.keuangan.laba_rugi.print.excel')}}?'+that.url.searchParams)
				            	},

				            	print: function(evt){
				            		evt.preventDefault();
				            		evt.stopImmediatePropagation();

				            		$.toast({
			                            text: "Sedang Mencetak Laporan",
			                            showHideTransition: 'slide',
			                            position: 'bottom-right',
			                            icon: 'info',
			                            hideAfter: 8000,
			                            showHideTransition: 'slide',
			                            allowToastClose: false,
			                            stack: false
			                        });

				            		window.print();

				            		// $('#pdfIframe').attr('src', '{{route('laporan.keuangan.laba_rugi.print')}}?'+that.url.searchParams)
				            	},

				            	humanizePrice: function(alpha){
				            	  var kl = alpha.toString().replace('-', '');
				                  bilangan = kl;
				                  var commas = '00';


				                  if(bilangan.split('.').length > 1){
				                    commas = bilangan.split('.')[1];
				                    bilangan = bilangan.split('.')[0];
				                  }
				                  
				                  var number_string = bilangan.toString(),
				                    sisa  = number_string.length % 3,
				                    rupiah  = number_string.substr(0, sisa),
				                    ribuan  = number_string.substr(sisa).match(/\d{3}/g);
				                      
				                  if (ribuan) {
				                    separator = sisa ? ',' : '';
				                    rupiah += separator + ribuan.join(',');
				                  }

				                  // Cetak hasil
				                  return rupiah+'.'+commas; // Hasil: 23.456.789
				                },

				                humanizeDate(date){
				                	let d = date.split('-')[2]+'/'+date.split('-')[1]+'/'+date.split('-')[0];

				                	return d;
				                },

				                typeChange: function(e){
				                	this.type = e;
				                },

				                akunChange:function(e){
				                	var ak2 = $.grep(this.akun, function(alpha){ return alpha.id >= e });

				                	this.akun2 = ak2;
				                },

				                prosesLaporan: function(evt){
				                	evt.preventDefault();
				                	evt.stopImmediatePropagation();

				                	if(this.validate()){
				                		this.stat = 'loading';
				                		$('#form-setting').submit();
				                	}
				                },

				                validate: function(){
				                	if($('#d1').val() == '' || $('#d2').val() == ''){
				                		$.toast({
				                            text: "Harap Lengkapi Data Inputan",
				                            showHideTransition: 'slide',
				                            position: 'top-right',
				                            icon: 'error',
				                            hideAfter: false
				                        });

				                        return false;
				                	}

				                	return true;
				                },

				                getNamaKelompok: function(index){
				                	var idx = this.kelompok.findIndex(alpha => alpha.ak_kelompok == index);

				                	return this.kelompok[idx].ak_nama;
				                },
				            }
			    		})
    	</script>
	</body>
</html>
