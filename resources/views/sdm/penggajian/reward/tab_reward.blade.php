<div class="tab-pane fade in" id="tabreward">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Reward & Punishment</h3>
			</div>
			<div class="header-block pull-right">
                <button class="btn btn-primary" onclick="getMasterReward()" data-toggle="modal" data-target="#modal_masterreward"><i class="fa fa-plus"></i>&nbsp;Master Reward</button>
                <button class="btn btn-primary" onclick="getMasterPunishment()" data-toggle="modal" data-target="#modal_masterpunishment"><i class="fa fa-plus"></i>&nbsp;Master Punishment</button>
			</div>
		</div>
		<div class="card-block">
			<section>
                <div class="row mb-3">
                    <div class="col-1">
                        <label>Periode</label>
                    </div>
                    <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-center periode_reward" id="periode_reward">
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-primary btn-cari" onclick="getDataRewardPunishment()"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" style="width: 100%" cellspacing="0" id="table_rewardpunishment">
                        <thead class="bg-primary">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Bonus/Reward</th>
                                <th>Punishment</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
			</section>
		</div>
	</div>
</div>
