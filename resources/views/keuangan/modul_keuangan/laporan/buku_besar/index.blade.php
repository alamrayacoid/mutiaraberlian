<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laporan Buku Besar</title>
        
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
		    	font-size: 8pt;
		    }

		    #table-data td{
		    	padding: 5px 10px;
		    	border: 1px solid #eee;
		    }

		    #table-data th{
		    	padding: 5px 10px;
		    	border: 1px solid #0099CC;
		    }

		    #table-data td.head{
		    	border: 1px solid #0099CC;
		    	background: #0099CC;
		    	color: white;
		    	font-weight: bold;
		    }

		    #table-data td.sub-head{
		    	border: 1px solid #0099CC;
		    	color: #333;
		    	font-weight: bold;
		    	text-align: center;
		    }

		    #table-data td.divide{
		    	background-color: #eee;
		    }

		    #contentnya{
	          width: 80%;
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
          	margin-top: -50px;
          }

          #table-data th{
             background-color: #0099CC !important;
             color: white;
             -webkit-print-color-adjust: exact;
          }

          #table-data td.divide{
             background-color: #eee !important;
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

			<div class="col-md-4 offset-4 ctn-nav" v-cloak>
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
			</div>

			<div class="container-fluid" style="background: none; margin-top: 70px; padding: 10px 30px;">
				<div id="contentnya">

					<?php 
						$tanggal_1 = switchBulan(explode('/', $_GET['d1'])[0]).' '.explode('/', $_GET['d1'])[1];

						$tanggal_2 = switchBulan(explode('/', $_GET['d2'])[0]).' '.explode('/', $_GET['d2'])[1];

						$type = "Semua Akun";

						if(!isset($_GET['semua']))
							$type = $_GET['akun1']." s/d ".$_GET['akun2'];
					?>					

					{{-- Judul Kop --}}

						<table width="100%" border="0" style="border-bottom: 1px solid #333;" v-if="pageNow == 1" v-cloak>
				          <thead>
				            <tr>
				              <th style="text-align: left; font-size: 14pt; font-weight: 600; padding-top: 10px;" colspan="2">Laporan Buku Besar <small>({{ $type }})</small></th>
				            </tr>

				            <tr>
				              <th style="text-align: left; font-size: 12pt; font-weight: 500" colspan="2">{{ jurnal()->companyName }}</th>
				            </tr>

				            <tr>
				              <th style="text-align: left; font-size: 8pt; font-weight: 500; padding-bottom: 10px;">(Angka Disajikan Dalam Rupiah, Kecuali Dinyatakan Lain)</th>

				              <th class="text-right" style="font-size: 8pt; font-weight: normal;">
				              	<b>{{ $tanggal_1 }}</b>&nbsp; s/d&nbsp; <b>{{ $tanggal_2 }}</b>
				              </th>
				            </tr>
				          </thead>
				        </table>

				    {{-- End Judul Kop --}}

			    	<div style="padding-top: 20px;">

						<table class="table" id="table-data" v-for="(data, index) in dataPrint" :style="(index > 0) ? 'margin-top: 40px;' : '0px'" v-cloak>
							<tbody>
								<tr>
									<th class="head" colspan="8" style="background-color: #0099CC; color: white; text-align: left;"> @{{ data.ak_id+" - "+data.ak_nama }} </th>
								</tr>

								<tr>
									<td width="8%" class="sub-head"> Tanggal </td>
									<td width="13%" class="sub-head"> No.Bukti </td>
									<td width="33%" class="sub-head"> Keterangan </td>
									<td width="3%" class="sub-head"> DK </td>
									<td width="7%" class="sub-head"> Kode Akun </td>
									<td width="12%" class="sub-head"> Debet </td>
									<td width="12%" class="sub-head"> Kredit </td>
									<td width="12%" class="sub-head"> Saldo </td>
								</tr>

								<tr>
									<td class="text-center">-</td>
									<td class="text-center">-</td>
									<td>Saldo Awal @{{ humanizeDate(data.ak_periode) }}</td>
									<td class="text-center" v-html="getDK(index)"></td>
									<td class="text-center">1.001.01</td>
									<td class="text-right">
										@{{ (getDK(index) == 'D') ? humanizePrice(data.ak_saldo_awal) : humanizePrice(0) }}
									</td>

									<td class="text-right">
										@{{ (getDK(index) == 'K') ? humanizePrice(data.ak_saldo_awal) : humanizePrice(0) }}
									</td>

									<td class="text-right">
										@{{ (saldoInfo[index].saldoAwal < 0) ? '('+humanizePrice(saldoInfo[index].saldoAwal)+')' : humanizePrice(saldoInfo[index].saldoAwal) }}
									</td>
								</tr>

								<tr>
									<td colspan="8" class="divide"></td>
								</tr>

								<template v-for="(detail, idx) in data.jurnal_detail">
									<tr>
										<td class="text-center" style="font-weight: 650;">@{{ humanizeDate(detail.jurnal.jr_tanggal_trans) }}</td>
										<td class="text-center" style="font-weight: 650;">@{{ detail.jurnal.jr_ref }}</td>
										<td style="font-weight: 650;">@{{ detail.jurnal.jr_keterangan }}</td>
										<td class="text-center" style="font-weight: 650;">@{{ detail.jrdt_dk }}</td>
										<td class="text-center" style="font-weight: 650;">@{{ detail.jrdt_akun }}</td>
										<td class="text-right" style="font-weight: 650;">
											@{{ (detail.jrdt_dk == 'D') ? humanizePrice(detail.jrdt_value) : humanizePrice(0) }}
										</td>

										<td class="text-right" style="font-weight: 650;">
											@{{ (detail.jrdt_dk == 'K') ? humanizePrice(detail.jrdt_value) : humanizePrice(0) }}
										</td>

										<td class="text-right" style="font-weight: 650;">
											@{{ (saldoInfo[index].saldo[idx] < 0) ? '('+humanizePrice(saldoInfo[index].saldo[idx])+')' : humanizePrice(saldoInfo[index].saldo[idx]) }}
										</td>
									</tr>

									<template v-if="showLawan">
										<tr v-for="lawan in detail.jurnal.detail" v-if="lawan.jrdt_akun != detail.jrdt_akun">
											<td class="text-center">@{{ humanizeDate(detail.jurnal.jr_tanggal_trans) }}</td>
											<td class="text-center">@{{ detail.jurnal.jr_ref }}</td>
											<td>@{{ detail.jurnal.jr_keterangan }}</td>
											<td class="text-center">@{{ lawan.jrdt_dk }}</td>
											<td class="text-center">@{{ lawan.jrdt_akun }}</td>
											<td class="text-right">
												@{{ (lawan.jrdt_dk == 'D') ? humanizePrice(lawan.jrdt_value) : humanizePrice(0) }}
											</td>

											<td class="text-right">
												@{{ (lawan.jrdt_dk == 'K') ? humanizePrice(lawan.jrdt_value) : humanizePrice(0) }}
											</td>

											<td class="text-right">
												{{-- @{{ (saldoInfo[index].saldo[idx] < 0) ? '('+humanizePrice(saldoInfo[index].saldo[idx])+')' : humanizePrice(saldoInfo[index].saldo[idx]) }} --}}
											</td>
										</tr>
									</template>

									<tr v-if="showLawan">
										<td colspan="8" style="background-color: #eee;" class="divide"></td>
									</tr>
								</template>
								
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
	            <div class="layout" style="width: 35%; min-height: 250px;">
	                <div class="top-popup" style="background: none;">
	                    <span class="title">
	                        Setting Laporan Buku Besar
	                    </span>

	                    <span class="close"><i class="fa fa-times" style="font-size: 12pt; color: #CC0000"></i></span>
	                </div>
	                
	                <div class="content-popup">
	                	<form id="form-setting" method="get" action="{{ route('laporan.keuangan.buku_besar') }}">
	                	<input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
	                    <div class="col-md-12">

	                        <div class="row mt-form">
	                            <div class="col-md-4">
	                                <label class="modul-keuangan">Rentang Waktu</label>
	                            </div>

	                            <div class="col-md-8">
	                            	<table width="100%" border="0">
	                            		<tr>
	                            			<td>
	                            				<vue-datepicker :name="'d1'" :id="'d1'" :title="'Tidak Boleh Kosong'" :readonly="true" :placeholder="'Pilih Tanggal'" :format="'mm/yyyy'" @input="d1Change" :styles="'font-size: 9pt;'"></vue-datepicker>
	                            			</td>
	                            			<td style="padding: 0px 10px;">-</td>
	                            			<td>
	                            				<vue-datepicker :name="'d2'" :id="'d2'" :title="'Tidak Boleh Kosong'" :readonly="true" :placeholder="'Pilih Tanggal'" :format="'mm/yyyy'" :styles="'font-size: 9pt;'"></vue-datepicker>
	                            			</td>
	                            		</tr>
	                            	</table>
	                            </div>
	                        </div>

	                        <div class="row mt-form">
	                            <div class="col-md-4">
	                                <label class="modul-keuangan">Akun Lawan</label>
	                            </div>

	                            <div class="col-md-7">
	                                <vue-select :name="'lawan'" :id="'lawan'" :options="lawanAkun" :styles="'width:100%'"></vue-select>
	                            </div>
	                        </div>

	                        <div class="row mt-form">
	                            <div class="col-md-4">
	                                <label class="modul-keuangan"></label>
	                            </div>

	                            <div class="col-md-7">
	                                <input type="checkbox" name="semua" title="Centang Untuk Menambahkan Nilai Lebih Bayar Ke Akun Dana Titipan" v-model="semua">

                                	<span style="font-size: 8pt; margin-left: 5px;">Tampilkan Semua Akun</span>
	                            </div>
	                        </div>

	                        <template v-if='!semua'>
		                        <div class="row mt-form" style="border-top: 1px solid #eee; padding-top: 20px;">
		                            <div class="col-md-4">
		                                <label class="modul-keuangan">Pilih Akun</label>
		                            </div>

		                            <div class="col-md-7">
		                                <vue-select :name="'akun1'" :id="'akun1'" :options="akun" :styles="'width:100%'" @input="akunChange"></vue-select>
		                            </div>
		                        </div>

		                        <div class="row mt-form">
		                            <div class="col-md-4">
		                                <label class="modul-keuangan">Sampai Akun</label>
		                            </div>

		                            <div class="col-md-7">
		                                <vue-select :name="'akun2'" :id="'akun2'" :options="akun2" :styles="'width:100%'"></vue-select>
		                            </div>
		                        </div>
		                    </template>

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
			    				showLawan: true,
			    				url: new URL(window.location.href),

			    				firstElement: 0,
			    				dataPage: 1,
			    				pageNow: 0,
			    				rowsCount: 5,

			    				nextDisabled: false,
			    				previousDisabled: true,

			    				dataSource: [],
			    				dataPrint: [],
			    				saldo: 0,

			    				// setting
			    					semua: true,
			    					akun: [],
			    					akun2: [],
			    					lawanAkun: [
			    						{
			    							id: 'true',
			    							text: 'Tampilkan Akun Lawan'
			    						},

			    						{
			    							id: 'false',
			    							text: 'Jangan Tampilkan Akun Lawan'
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
				            	$('#d2').val('{{ $_GET['d2'] }}');
				            	$('#lawan').val('{{ $_GET['lawan'] }}').trigger('change.select2');

				            	that = this;

				            	axios.get('{{route('laporan.keuangan.buku_besar.data_resource')}}?'+that.url.searchParams)
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

			                                if(response.data.akun.length > 0){
			                                	this.akun = response.data.akun;
			                                	this.akun2 = response.data.akun;
			                                }

			                                this.showLawan = (response.data.requestLawan == 'true') ? true : false;
			                                this.semua = (response.data.requestSemua == 'on') ? true : false;

			                                if(!this.semua){


			                                	setTimeout(function(){
		                                			$('#akun1').val(response.data.akun1).trigger('change.select2');
			                                		$('#akun2').val(response.data.akun2).trigger('change.select2');
			                                	}, 0);

		                                		this.akunChange(response.data.akun1);
			                                }

			                                $('#loading-popup').ezPopup('close');
			                                this.calculatingDK();
			                            })
			                            .catch((e) => {
			                            	this.textLoading = "Data Laporan Bermasalah. Segera Hubungi Developer. message : "+e;
			                            })
				            },

				            computed: {
				            	saldoInfo: function(){
				            		that = this;
				            		var clock = []; var movement = 0

				                	$.each(this.dataPrint, function(a, b){
				                		
				                		var stack = [];
				                		movement = parseFloat(b.ak_saldo_awal);

				                		$.each(b.jurnal_detail, function(c, d){
				                			if(d.jrdt_dk != b.ak_posisi)
				                				movement -= parseFloat(d.jrdt_value);
				                			else
				                				movement += parseFloat(d.jrdt_value);

				                			stack.push(movement)
				                		})	

				                		clock.push(
				                			{
				                				saldoAwal 	: b.ak_saldo_awal,
				                				saldo 		: stack
				                			}
				                		);
				                	})

				                	return clock;
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

				                    $('#pdfIframe').attr('src', '{{route('laporan.keuangan.buku_besar.print.pdf')}}?'+that.url.searchParams)

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

			                        $('#pdfIframe').attr('src', '{{route('laporan.keuangan.buku_besar.print.excel')}}?'+that.url.searchParams)
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

				            		// $('#pdfIframe').attr('src', '{{route('laporan.keuangan.buku_besar.print')}}?'+that.url.searchParams)
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

				                d1Change: function(e){
				                	$('#d2').val("");
				                	$('#d2').datepicker("setStartDate", e);
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

				                getDK: function(index){
				                	var data = this.dataPrint[index];

				                	if(data.ak_saldo_awal < 0){
				                		if(data.ak_posisi == "D")
				                			return "K";
				                		else
				                			return "D";
				                	}else{
				                		return data.ak_posisi;
				                	}
				                },
				            }
			    		})
    	</script>
	</body>
</html>
