Vue.component('vue-datatable-v2', {
  data(){
    return {
      dataTab: [],
      _backup: [],
      _recursive: [],
      search: '',
      fullPage: 1,
      page: 1,
      firstIndex: 0,
      lastIndex: 0,
      dataPage: 10,
      sortBy: 'none',
      order: 'asc'
    }
  },
  props:{
    config: {
      type: Object,
      required: true,
    }
  },
  created: function(){
    this.$data._recursive = JSON.parse(JSON.stringify(this.config.feeder.data));
    this.sortBy = this.config.feeder.column[0].conteks;
  },
  mounted: function(){
    console.log("Datatables v2 Ready...");
    this.renderData();
  },
  methods:{
    pageIncrease: function(e){
      e.preventDefault();
      this.page++;
    },

    pageDecrease: function(e){
      e.preventDefault();
      this.page--;
    },

    renderData: function(){
      // alert('e');
      var that = this;
      this.dataPage = (this.config.config && this.config.config.dataPerPage) ? this.config.config.dataPerPage : this.dataPage;

      if(this.$data._recursive.length / this.dataPage < 1){
        this.fullPage = Math.floor(this.$data._recursive.length / this.dataPage) + 1;
        // alert('a')
      }else if((this.$data._recursive.length / this.dataPage) % 1 == 0){
        this.fullPage = Math.floor(this.$data._recursive.length / this.dataPage);
        // alert('b')
      }else if(this.$data._recursive.length / this.dataPage > 1){
        this.fullPage = Math.floor(this.$data._recursive.length / this.dataPage) + 1;
        // alert('c')
      }

      this.firstIndex = 0;
      this.page = 1;
      this.lastIndex = this.firstIndex + (this.dataPage - 1);

      this.sortManual(this.$data._recursive, this.sortBy, this.order);
      this.dataTab = $.grep(this.$data._recursive, function(n, i){ return i >= that.firstIndex && i <= that.lastIndex});
    },

    sortMe: function(key){

      var array = this.$data._recursive;

      if(key == this.sortBy){
        if(this.order == 'asc'){
          this.order = 'desc';
        }
        else{
          this.order = 'asc';
        }

      }else{
        this.order = 'asc';
        this.sortBy = key;
      }
      
      // this.$data._recursive = [];

      this.renderData();

      // console.log(this.config.feeder.data);
      // alert('sortBy: '+this.sortBy+' & order: '+this.order);
    },

    sortManual: function(array, sortBy, key) {
      if(key == 'asc'){
        this.$data._recursive = this.$data._recursive.sort(function (a, b) {
            var x = a[sortBy]; var y = b[sortBy];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
      }else{
        this.$data._recursive = this.$data._recursive.sort(function (a, b) {
            var x = a[sortBy]; var y = b[sortBy];
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        });
      }
    },
  },

  watch: {
    tableData: function(e){
       this.$data._recursive = JSON.parse(JSON.stringify(e));
       this.renderData();
    },

    page: function(e){
      var that = this;

      this.firstIndex = 0 + ((this.dataPage * this.page) - this.dataPage);
      this.lastIndex = this.firstIndex + (this.dataPage - 1);
      // alert(this.firstIndex+' - '+this.lastIndex);
      this.dataTab = $.grep(this.$data._recursive, function(n, i){ return i >= that.firstIndex && i <= that.lastIndex});
    },

    search: function(e){
      this.$data._recursive = JSON.parse(JSON.stringify(this.config.feeder.data));
      this.sortBy = this.config.feeder.column[0].conteks;
      this.order = 'asc';

      if(e == '') { this.renderData(); return };
      var that = this;

      var data = this.$data._recursive.filter(function(o){
        for(var alpha = 0; alpha < that.config.feeder.column.length; alpha++){
          if(o[that.config.feeder.column[alpha].conteks].toString().toUpperCase().includes(e.toUpperCase())) return o;
        }
      })

      this.$data._recursive = data;

      this.renderData();
    }
  },
  computed:{
    tableData: function(){
      // alert('computed');
      return this.config.feeder.data;
    } 
  },
  template: `
      <div id="datatable-vue" class="row" style="padding: 0px; margin-top: 10px;">
          <div class="col-md-4">
            <input type="text" class="form-control" style="height: 30px; font-size: 9pt;" placeholder="Lakukan Pencarian ..." v-model="search"> 
          </div>

          <div class="col-md-8 text-right button-wrapper">
            <template v-if="config.config && config.config.buttonHelper">
              <button v-for="btn in config.config.buttonHelper" type="button" class="btn btn-xs" v-html="btn.text" @click="(btn.onClick) ? btn.onClick($event) : ''"></button>
            </template>
          </div>

          <div class="col-md-12 table-wrapper" style="margin-top: 15px;">
            <table class="table table-bordered table-stripped" style="margin-bottom: 0px;">
                <thead>
                    <tr>
                      <th v-if="config.addition && config.addition.columnNumber && config.addition.columnNumber.show" :width="config.addition.columnNumber.width" style="text-align:center;">No</th>
                      <th v-for="(data, index) in config.feeder.column" :style="data.style" @click="sortMe(data.conteks)" style="text-align: center;">
                        {{ data.text }} &nbsp;
                        <i class="fa fa-exchange fa-rotate-90" v-if="sortBy != data.conteks"></i>
                        <i class="fa fa-sort-amount-asc" v-if="sortBy == data.conteks && order =='asc'"></i>
                        <i class="fa fa-sort-amount-desc" v-if="sortBy == data.conteks && order =='desc'"></i>
                      </th>

                      <th v-if="config.addition && config.addition.columnButton && config.addition.columnButton.show" :width="config.addition.columnButton.width" style="text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                  <tr v-for="(data, index) in dataTab">
                    <td v-if="config.addition && config.addition.columnNumber && config.addition.columnNumber.show" style="text-align: center">{{ index+1 }}</td>
                    <td v-for="(column, alpha) in config.feeder.column"
                        v-html="(!column.overide) ? data[column.conteks] : column.overide(data[column.conteks])"
                        :style="column.childStyle">
                    </td>

                    <td v-if="config.addition && config.addition.columnButton && config.addition.columnButton.show" style="text-align: center">
                      <div style="display: inline-block; padding: 0px 1px;" v-for="btn in config.addition.columnButton.content" v-html="btn.html" @click="(btn.onClick) ? btn.onClick(data) : ''"></div>
                    </td>
                  </tr>
                </tbody>
            </table>

            <center style="padding: 8px; border-bottom: 1px solid #eee;" v-if="!config.feeder.data.length">
              <i class="fa fa-table"></i> &nbsp; Tabel Data
            </center>
          </div>

          <div class="col-md-12 bottom-wrap">
            <div class="row">
              <div class="col-md-6 info-wrap">
                  <span>Menampilkan {{ dataPage }} data setiap 1 halaman</span>  
              </div>

              <div class="col-md-6" style="padding: 10px 15px 0px 0px; text-align: right;">
                <div class="btn-group" role="group" aria-label="...">
                  <button type="button" class="btn btn-success btn-xs" :disabled="page == 1" @click="pageDecrease">Sebelumnya</button>
                  <button type="button" class="btn btn-success btn-xs" :disabled="page == fullPage" @click="pageIncrease">Selanjutnya</button>
                </div>
              </div>
            </div>
          </div>
      </div>
  `
});