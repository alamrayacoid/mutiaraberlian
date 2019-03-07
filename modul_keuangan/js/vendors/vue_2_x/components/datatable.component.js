Vue.component('vue-datatable',{
  data(){
    return {
      dataTab: [],
      search: '',
      search_context: '',
      sortBy: '',
      order: '',
    }
  },
  props:{
    data_resource:{
      type:Array,
      required:true
    },
    columns:{
      type:Array,
      required:true
    },
    selectable:{
      type:Boolean,
      required:false
    },
    ajax_on_loading:{
      type:Boolean,
      required:false
    },
    index_column:{
      type:String,
      required:true
    }
  },
  mounted: function(){
    console.log("Datatables Ready...");
    this.search_context = (this.columns.length > 0) ? this.columns[0].context : '';
    this.dataTab = this.data_resource;
  },
  methods:{
      selectMe: function(index){
        this.$emit('selected', index);
      },
      hoverMe: function(index){
        this.$emit('hovered', index);
      },
      changeConteks: function(alpha){
        this.search_context = alpha.target.options[alpha.target.options.selectedIndex].getAttribute('value');
      }
  },
  watch: {
    data_resource: function(value){
      this.dataTab = this.data_resource;
      // console.log(this.dataTab)
    },
    columns: function(){
      // alert('okee');
      this.search_context = (this.columns.length > 0) ? this.columns[0].context : '';
      // console.log(this.search_context);
    },
    search: function(value){
      if(value == ""){ this.dataTab = this.data_resource; return }

      this.search_context = $('#vue-datatable-search-context').val();
      mine = this;

      // console.log(this.data_resource);

      var data = this.data_resource.filter(function(o){
        if(o[mine.search_context].toUpperCase().includes(value.toUpperCase())) return o;
      })

      this.dataTab = data;
    }
  },
  computed:{
    
  },
  template: `

      <div class="row">
        <div class="col-md-3">
          <select class="form-control modul-keuangan" style="cursor: pointer" id="vue-datatable-search-context" title="Pencarian Berdasarkan"  @change="changeConteks">
              <option :value="column.context" v-for="column in columns">{{ column.name }}</option>
          </select>
        </div>

        <div class="col-md-3" style="padding:0px 10px 5px 0px;">
          <input type="text" class="form-control modul-keuangan" v-model="search" style="background:white;" placeholder="Kata Kunci...">
        </div>

        <div class="col-md-12" style="margin-top: 10px; height: 300px; overflow-y: scroll; background: white">
          <table class="table table-bordered table-stripped" id="vue-datatable">
            <thead>
              <tr>
                <th v-for="column in columns" :width="column.width">{{ column.name }}</th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="ajax_on_loading">
                <td :colspan="columns.length" style="text-align: center"> Sedang Mengumpulkan Data. Harap Tunggu...  </td>
              </tr>

              <tr v-if="dataTab.length == 0 && !ajax_on_loading">
                <td :colspan="columns.length" style="text-align: center"> Tidak Ada Data </td>
              </tr>

              <tr v-for="data in dataTab" :class="selectable ? 'selectable' : ''" @click="selectMe(data[index_column])" @mouseover="hoverMe(data[index_column])">
                <td v-for="column in columns" :style="column.childStyle" v-html="(!column.override) ? data[column.context] : column.override(data[column.context])"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
  `
});