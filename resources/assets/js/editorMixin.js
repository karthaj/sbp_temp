import { mapActions, mapGetters, mapMutations } from 'vuex'

export default {

  data() {
    return {
      currentUrl: $("tpframe").attr('src')
    }
  },
  computed: {
    ...mapGetters({

        settings: 'settings',
        themeSettings: 'themeSettings',
        sections: 'sections',
        products: 'products',
        brands: 'brands',
        menus: 'menus',
        categories: 'categories',
        shopbox: 'shopbox'

    }),
    setting: {
      get: function () {

        if(this.section) {
        
          if(this.blockIndex) {

            return this.settings.sections[this.section].blocks[this.blockIndex].settings[this.configs.id];

          }

          return this.settings.sections[this.section].settings[this.configs.id];

        } 

        return this.settings[this.configs.id];

      },
      set: function (value) {

        if(this.section) {
          
          if(this.blockIndex) {

            this.settings.sections[this.section].blocks[this.blockIndex].settings[this.configs.id] = value;

          } else {

            this.settings.sections[this.section].settings[this.configs.id] = value;

          }

        } else {

          this.settings[this.configs.id] = value;

        }

      }
    }
  },
  methods: {
    ...mapActions({

        getProducts: 'getProducts',
        getBrands: 'getBrands',
        getMenus: 'getMenus',
        getCategories: 'getCategories'

    }),
    ...mapMutations({

        setSettings: 'setSettings',
        setThemeSettings: 'setThemeSettings',
        setSections: 'setSections',
        setShopbox: 'setShopbox'

    }),
    getComponentID (id, section, blockIndex = null) {

      if(section && blockIndex) {
        return section + '_block_' + blockIndex + '_' + id;
      } 

      if(section) {
        return section + '_' + id;
      } 
           
      return id;

    },

    // getSectionSettings (id, section) {

    //   if(section) {

    //     var settings = {
    //       sections: {}
    //     };

    //     settings.sections[section] = this.settings.sections[section];

    //     return settings;


    //   }

    //   var settings = {};
      
    //   settings[id] = this.settings[id];

    //   return settings;

    // },

    loadStyles (css) {

      var path = this.shopbox.store_url + '/stores/' + this.shopbox.domain + '/themes/' + this.shopbox.theme.handle + '/assets/customized.css';
      var frame = document.getElementById("tpframe");
      var style = document.createElement("style");

      var att = document.createAttribute("data-href");
      att.value = path;   
      style.setAttributeNode(att); 
      style.innerHTML=css;

      $("#tpframe").contents().find("[href='"+path+"']").remove();
      $("#tpframe").contents().find("[data-href='"+path+"']").remove();

      frame.contentDocument.head.appendChild(style);         
        
    }


  }

}