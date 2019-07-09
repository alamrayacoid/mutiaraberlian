Vue.component('vue-datatable',{
  data(){
    return {
      dataTab: [],
      search: '',
      search_context: '',
      sortBy: '',
      order: '',
      selectable: true,
      index_column: 'aaa',
    }
  },
  props:{
    resource:{
      type:Object,
      required:true
    },
    // columns:{
    //   type:Array,
    //   required:true
    // },
    // selectable:{
    //   type:Boolean,
    //   required:false
    // },
    // ajax_on_loading:{
    //   type:Boolean,
    //   required:false
    // },
    // index_column:{
    //   type:String,
    //   required:true
    // }
  },
  mounted: function(){
    console.log("Datatables Ready...");
    this.dataTab = this.resource.data.source;

    if(this.resource.option && this.resource.option){
      this.selectable = this.resource.option.selectable
    }

    if(this.resource.option && this.resource.option.index_column){
      this.index_column = this.resource.option.index_column
    }else{
      this.index_column = this.resource.data.column[0].context;
    }

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
      },
      onTable: function(e){
        if(e.which == 40){
          e.preventDefault();  
          $(e.target).val();
          $(e.target).closest('.row').find('.vue-datatable-row-data').first().focus();
        }
      },
      nextRow: function(e){    
        switch(e.which){
          case 40:
              e.preventDefault(); 
              if($(e.target).closest('.row').find('.vue-datatable-row-data').eq(($(e.target).index() + 1)).index() < 0){
                $(e.target).closest('.row').find('.vue-datatable-row-data').first().focus();
              }
              else{
                $(e.target).closest('.row').find('.vue-datatable-row-data').eq(($(e.target).index() + 1)).focus();
              }
             break;

          case 38:
              e.preventDefault(); 
              if($(e.target).closest('.row').find('.vue-datatable-row-data').eq(($(e.target).index() + 1)) < 0){
                $(e.target).closest('.row').find('.vue-datatable-row-data').last().focus();
              }
              else{
                $(e.target).closest('.row').find('.vue-datatable-row-data').eq(($(e.target).index() - 1)).focus();
              }
            break;
          case 13:
              e.preventDefault(); 
              this.$emit('selected', $(e.target).data('id'));
        }
      }
  },
  watch: {
    data: function(value){
      this.dataTab = this.data;
    },
    columns: function(){
      // alert('okee');
      this.search_context = (this.resource.data.columns.length > 0) ? this.resource.data.columns[0].context : '';
      // console.log(this.search_context);
    },
    search: function(value){
      if(value == ""){ this.dataTab = this.resource.data.source; return }

      this.search_context = $('#vue-datatable-search-context').val();
      mine = this;

      var data = this.resource.data.source.filter(function(o){
        for(var alpha = 0; alpha < mine.resource.data.column.length; alpha++){
          if(o[mine.resource.data.column[alpha].context].toString().toUpperCase().includes(value.toUpperCase())) return o;
        }
      })

      this.dataTab = data;
    }
  },
  computed:{
    data: function(){
      return this.resource.data.source;
    }
  },
  template: `

      <div class="row">
        <!-- <div class="col-md-2 col-sm-4 col-xs-4 form-datatable-vue" style="padding: 0px 0px 0px 15px;">
          <select class="form-control modul-keuangan" style="cursor: pointer" id="vue-datatable-search-context" title="Pencarian Berdasarkan" @change="changeConteks">
              <option :value="column.context" v-for="column in resource.data.column">{{ column.name }}</option>
          </select>
        </div> -->

        <div class="col-md-5 col-sm-5 col-xs-5 form-datatable-vue" style="padding: 0px 0px 0px 20px;">
          <input type="text" class="form-control modul-keuangan vue-datatable-search" v-model="search" style="background:white;" placeholder="Kata Kunci..." @keydown="onTable">
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 vue-datatable-wrap" style="margin-top: 15px; height: 300px; overflow-y: scroll; background: white">
          <table class="table" id="vue-datatable">
            <thead>
              <tr>
                <th v-for="column in resource.data.column" :width="column.width">{{ column.name }}</th>
              </tr>
            </thead>

            <tbody>
              <!-- <tr v-if="ajax_on_loading">
                <td :colspan="resource.data.column.length" style="text-align: center"> Sedang Mengumpulkan Data. Harap Tunggu...  </td>
              </tr> -->

              <tr v-if="dataTab.length == 0">
                <td :colspan="resource.data.column.length" style="text-align: center"> Tidak Ada Data </td>
              </tr>

              <tr :data-id="data[index_column]" :tabindex="idx" v-for="(data, idx) in dataTab" :class="selectable ? 'selectable vue-datatable-row-data' : 'vue-datatable-row-data'" @click="selectMe(data[index_column])" @mouseover="hoverMe(data[index_column])" @keydown="nextRow">
                <td v-for="column in resource.data.column" :style="column.childStyle" v-html="(!column.override) ? data[column.context] : column.override(data[column.context])"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
  `
});