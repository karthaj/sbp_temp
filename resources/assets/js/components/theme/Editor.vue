<template>
<div id="quickview" class="quickview-wrapper open hidden-xs-down" data-pages="quickview">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs">
    <li class="active" data-target="#section-settings" data-toggle="tab">
      <a href="#">Sections</a>
    </li>
    <li data-target="#theme-settings" data-toggle="tab">
      <a href="#">settings</a>
    </li>
  </ul>
  <a class="btn-link quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"></a>
  <!-- Tab panes -->
  
  <div class="tab-content">
    <div class="tab-pane no-padding" id="theme-settings">

        <div class="view-port clearfix" id="theme-setting-list">
          <div class="view bg-white">
            <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
              <div class="navbar navbar-default">
                <div class="navbar-inner text-center">{{ shopbox.theme.name }}</div>
              </div>
              <div class="list-view-group-container">

                <content-placeholders :centered="true" v-if="!themeSettings.length" v-for="index in 20" :key="index">
                  <content-placeholders-heading  />
                </content-placeholders>

                <ul v-if="themeSettings.length">
                  <li class="chat-user-list clearfix" v-for="setting, index in themeSettings">
                    <a data-view-animation="push-parrallax" data-view-port="#theme-setting-list" data-navigate="view" :data-toggle-view="'#settingViewindex'+index" class="" href="javascript:;" @click.prevent="openSettingView(str_slug(setting.name))"><p class="p-l-10 font-weight-bold">
                        <span class="text-master">{{ setting.name }}</span>
                    </p></a>
                  </li>
                </ul>
                <reset v-if="themeSettings.length" :endpoint="resetUrl"></reset>
              </div>
            </div>
          </div>
          <div  class="view bg-white">
            <div :id="'settingViewindex'+index" :data-view-id="str_slug(setting.name)" class="view bg-white" v-for="setting, index in themeSettings">
              <div class="navbar navbar-default">
                <div class="navbar-inner">
                  <a href="javascript:;" class="link text-master inline action p-l-10 p-r-10" data-navigate="view" data-view-port="#theme-setting-list" data-view-animation="push-parrallax">
                    <i class="pg-arrow_left"></i>
                  </a>
                  <div class="view-heading">{{ setting.name }}</div>
                </div>
              </div>
              <div class="list-view-wrapper">
                <div class="list-view-group-container" v-for="option in setting.settings">
                  <div class="text-uppercase px-3" v-if="option.type == 'header'">
                    <strong>{{ option.content }}</strong>
                  </div>
                  <image-picker :theme-id="theme" :placeholder="placeholder" v-if="option.type === 'image_picker'" :configs="option" :section="setting.section" :image-path="imagePath"></image-picker>
                  <selector v-else-if="option.type === 'select'" :configs="option"
                  :endpoint="configUrl" :process="option.process"></selector>              
                  <textbox v-else-if="option.type === 'text'" :configs="option"
                  :endpoint="configUrl"></textbox>              
                  <category-select  v-else-if="option.type === 'category'" :configs="option" :section="setting.section" :endpoint="configUrl"></category-select> 
                  <color-picker v-else-if="option.type === 'color'" :configs="option"
                  :endpoint="configUrl"></color-picker>
                  <checkbox v-else-if="option.type === 'checkbox'" :configs="option"></checkbox>
                  <radio-button v-else-if="option.type === 'radio'" :configs="option"
                  :endpoint="configUrl"></radio-button>
                  <menu-selector v-else-if="option.type === 'link_list'" :configs="option"
                  :endpoint="configUrl"></menu-selector>
                  <number v-else-if="option.type === 'number'" :configs="option" :endpoint="configUrl"></number>
                  <custom-content v-else-if="option.type === 'html'" :configs="option" :section="setting.section"></custom-content>
                  <font-picker v-else-if="option.type === 'font'" :configs="option"
                  :endpoint="configUrl"></font-picker>
                  <codemirror v-else-if="option.type === 'code'" :configs="option"
                  :endpoint="configUrl"></codemirror>         
                </div>
                <br><br>
              </div>
            </div>
          </div>
        </div>
       
    </div>
    <div class="tab-pane active no-padding" id="section-settings">

        <div class="view-port clearfix" id="section-setting-list">
          <div class="view bg-white">
            <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
              <div class="navbar navbar-default">
                <div class="navbar-inner text-center">{{ shopbox.theme.name }}</div>
              </div>
              <div class="list-view-group-container mb-5">
                <ul>
                  <!-- <content-placeholders :centered="true" v-if="loading && !sectionSettings.length" v-for="index in 20" :key="index">
                    <content-placeholders-heading  />
                  </content-placeholders> -->
                  <li class="chat-user-list clearfix" v-if="header">
                      <a @click.prevent="editingSection(header.section)" data-view-animation="push-parrallax" data-view-port="#section-setting-list" data-navigate="view" data-toggle-view="#sectionViewHeader" class="" href="javascript:;"><p class="p-l-10 font-weight-bold">
                          <span class="text-master">{{ header.name }}</span>
                      </p></a>
                  </li>
                  
                  <p v-if="!sectionSettings.length" class="font-weight-bold text-center mt-3 mb-0">Page Content</p>
                  <hr class="mb-0">

                  <draggable :list="sectionSettings" :options="{handle: '.handle'}" @start="drag=true" @end="drag=false" @change="update">
                    <li class="chat-user-list clearfix" v-for="setting, index in sectionSettings" v-if="setting.type === 'content_for_index'">
                      <i class="aapl-text-align-justify handle"></i>
                      <a @click.prevent="editingSection(setting.section)" data-view-animation="push-parrallax" data-view-port="#section-setting-list" data-navigate="view" :data-toggle-view="'#sectionViewindex'+index" class="" href="javascript:;">
                        <p class="p-l-10 font-weight-bold">
                            <span class="text-master">{{ displayName(settings.sections[setting.section], setting.name)  }}</span>
                        </p>
                      </a>
                      <i class="aapl-eye-crossed pr-3" v-if="settings.sections[setting.section].disabled" 
                      @click.prevent="toggleVisibility(setting.section, false)"></i>
                      <i class="aapl-eye pr-3" v-else
                       @click.prevent="toggleVisibility(setting.section, true)"></i>
                    </li>
                  </draggable>
                  <li class="chat-user-list clearfix" v-for="setting, index in sectionSettings" v-if="setting.type !== 'content_for_index' && setting.type !== 'header' && setting.type !== 'footer'">
                      <a data-view-animation="push-parrallax" data-view-port="#section-setting-list" data-navigate="view" :data-toggle-view="'#sectionViewindex'+index" class="" href="javascript:;"><p class="p-l-10 font-weight-bold">
                          <span class="text-master">{{ setting.name }}</span>
                      </p></a>
                  </li>

                  <li v-if="homePage && sections.length" class="chat-user-list clearfix">
                    <i class="aapl-plus-square"></i>
                    <a data-view-animation="push-parrallax" data-view-port="#section-setting-list" data-navigate="view" data-toggle-view="#sectionsView" class="" href="javascript:;">
                      <p class="p-l-10 font-weight-bold">
                          <span class="text-master">Add section</span>
                      </p>
                    </a>
                  </li>

                  <li class="chat-user-list clearfix" v-if="footer">
                      <a @click.prevent="editingSection(footer.section)" data-view-animation="push-parrallax" data-view-port="#section-setting-list" data-navigate="view" data-toggle-view="#sectionViewFooter" class="" href="javascript:;"><p class="p-l-10 font-weight-bold">
                          <span class="text-master">{{ footer.name }}</span>
                      </p></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          
          <div  class="view bg-white">
            <div :id="'sectionViewindex'+index" :data-view-id="setting.section" class="view bg-white" v-for="setting, index in sectionSettings">
              <div class="navbar navbar-default">
                <div class="navbar-inner">
                  <a href="javascript:;" @click.prevent="closeSectionPanel(setting.section)" class="link text-master inline action p-l-10 p-r-10" data-navigate="view" data-view-port="#section-setting-list" data-view-animation="push-parrallax">
                    <i class="pg-arrow_left"></i>
                  </a>
                  <div class="view-heading">{{ displayName(settings.sections[setting.section], setting.name) }}</div>
                </div>
              </div>
              <div class="list-view-wrapper">
                <div class="list-view-group-container" v-for="option in setting.settings">
                  <div class="text-uppercase px-3" v-if="option.type == 'header'">
                    <strong>Header</strong>
                  </div>
                  <image-picker :theme-id="theme" :placeholder="placeholder" v-if="option.type === 'image_picker'" :configs="option" :section="setting.section" :image-path="imagePath"></image-picker>

                  <selector v-else-if="option.type === 'select'" :configs="option"  
                  :section="setting.section" :endpoint="configUrl"></selector>              
                  <textbox v-else-if="option.type === 'text'" :configs="option" :section="setting.section"
                  :endpoint="configUrl"></textbox>   

                  <category-select  v-else-if="option.type === 'category'" :categories="categories" :configs="option" :section="setting.section" :endpoint="configUrl"></category-select> 

                  <color-picker v-else-if="option.type === 'color'" :configs="option" :section="setting.section"></color-picker>

                  <checkbox v-else-if="option.type === 'checkbox'" :configs="option" :section="setting.section"></checkbox>

                  <radio-button v-else-if="option.type === 'radio'" :configs="option" :section="setting.section" :endpoint="configUrl"></radio-button>

                  <menu-selector v-else-if="option.type === 'link_list'" :configs="option" :section="setting.section" :endpoint="configUrl"></menu-selector>

                  <custom-content v-else-if="option.type === 'html'" :configs="option" :section="setting.section"></custom-content>

                  <richtext v-else-if="option.type === 'richtext'" :configs="option" :section="setting.section"
                  :endpoint="configUrl"></richtext> 

                  <number v-else-if="option.type === 'number'" :configs="option" :section="setting.section"
                   :endpoint="configUrl"></number>

                  <codemirror v-else-if="option.type === 'code'" :configs="option" :section="setting.section"
                  :endpoint="configUrl"></codemirror> 
                </div>
                <div v-if="setting.blocks" class="px-3">
                    <hr>
                    <div class="d-flex justify-content-between">
                      <span class="form-group">
                      <label>content</label>
                    </span>
                    <a href="#" @click.prevent="addContent(setting.blocks[0].type, setting.defaults.blocks, setting.section)">
                      <i class="aapl-plus-square"></i> 
                      <span class="text-master">Add</span>
                    </a>
                    </div>
                    <ul class="list-group mt-3">
                      <draggable :list="settings.sections[setting.section].block_order" :options="{handle: '.handle'}" @start="drag=true" @end="drag=false"  @change="changeBlockOrder(setting.section)">
                        <div v-for="(block, index) in settings.sections[setting.section].block_order" :key="index">
                          <li class="list-group-item list-group-item-light justify-content-between align-items-center" :data-block-id="block">
                            <span class="thumbnail__preview" v-html="displayImage(settings.sections[setting.section].blocks[block])"></span>
                            <a class="theme-editor-list__item btn-xs" data-toggle="collapse" :href="'#content-panel-'+setting.section+'-'+index" :aria-controls="'content-panel-'+setting.section+'-'+index" role="button">
                              <span class="text-master thumbnail__name">{{ displayName(settings.sections[setting.section].blocks[block]) }}</span>
                            </a> 
                            <i class="aapl-text-align-justify handle"></i>
                          </li>
                          <div :id="'content-panel-'+setting.section+'-'+index" class="collapse py-4" :aria-labelledby="'content-panel-'+setting.section+'-'+index">
                            <template v-for="option in setting.blocks[0].settings">
                              <image-picker placeholder="https://via.placeholder.com/1920x550?text=image" v-if="option.type === 'image_picker'" :configs="option" :section="setting.section" :theme-id="theme" :image-path="imagePath" :block-index="block"></image-picker>
                              <product-select v-else-if="option.type === 'product'" :configs="option" :section="setting.section" :block-index="block"></product-select>
                              <brand-select v-else-if="option.type === 'brand'" :configs="option" :section="setting.section" :block-index="block"></brand-select>
                              <textbox v-else-if="option.type === 'text'" :configs="option" :section="setting.section" :block-index="block"></textbox> 
                            </template>
                            <div class="d-flex justify-content-center mt-3">
                              <a href="#" @click.prevent="removeContent(block, setting.section)"><i class="aapl-trash2" ></i> remove content</a>
                            </div>
                          </div>
                        </div>
                      </draggable>
                    </ul>
                </div>
                <remove-section :section="String(setting.section)" :settings="settings"></remove-section>
                <br><br><br><br>
              </div>
            </div>

            <div id="sectionViewHeader" :data-view-id="header.section" class="view bg-white" v-if="header">
              <div class="navbar navbar-default">
                <div class="navbar-inner">
                  <a href="javascript:;" @click.prevent="closeSectionPanel(header.section)" class="link text-master inline action p-l-10 p-r-10" data-navigate="view" data-view-port="#section-setting-list" data-view-animation="push-parrallax">
                    <i class="pg-arrow_left"></i>
                  </a>
                  <div class="view-heading">{{ header.name }}</div>
                </div>
              </div>
              <div class="list-view-wrapper">
                <div class="list-view-group-container" v-for="option in header.settings">
                  <div class="text-uppercase px-3" v-if="option.type == 'header'">
                    <strong>Header</strong>
                  </div>
                  <image-picker :theme-id="theme" :placeholder="placeholder" v-if="option.type === 'image_picker'" :configs="option" :section="header.section" :image-path="imagePath"></image-picker>
                  <selector v-else-if="option.type === 'select'" :configs="option"  
                  :section="header.section" :endpoint="configUrl"></selector>              
                  <textbox v-else-if="option.type === 'text'" :configs="option" :section="header.section"
                  :endpoint="configUrl"></textbox>              
                  <category-select  v-else-if="option.type === 'category'" :categories="categories" :configs="option" :section="header.section" :endpoint="configUrl"></category-select> 
                  <color-picker v-else-if="option.type === 'color'" :configs="option" :section="header.section" :endpoint="configUrl"></color-picker>
                  <checkbox v-else-if="option.type === 'checkbox'" :configs="option" :section="header.section"></checkbox>
                  <radio-button v-else-if="option.type === 'radio'" :configs="option" :section="header.section" :endpoint="configUrl"></radio-button>
                  <menu-selector v-else-if="option.type === 'link_list'" :configs="option" :section="header.section" :endpoint="configUrl"></menu-selector>
                  <custom-content v-else-if="option.type === 'html'" :configs="option" :section="header.section"></custom-content>
                   
                </div>
                <div style="margin-bottom: 100px;"></div>
              </div>
            </div>

            <div id="sectionViewFooter" :data-view-id="footer.section" class="view bg-white" v-if="footer">
              <div class="navbar navbar-default">
                <div class="navbar-inner">
                  <a href="javascript:;" @click.prevent="closeSectionPanel(footer.section)" class="link text-master inline action p-l-10 p-r-10" data-navigate="view" data-view-port="#section-setting-list" data-view-animation="push-parrallax">
                    <i class="pg-arrow_left"></i>
                  </a>
                  <div class="view-heading">{{ footer.name }}</div>
                </div>
              </div>
              <div class="list-view-wrapper">
                <div class="list-view-group-container" v-for="option in footer.settings">
                  <div class="text-uppercase px-3" v-if="option.type == 'header'">
                    <strong>Footer</strong>
                  </div>
                  <image-picker :theme-id="theme" :placeholder="placeholder" v-if="option.type === 'image_picker'" :configs="option" :section="footer.section" :image-path="imagePath"></image-picker>
                  <selector v-else-if="option.type === 'select'" :configs="option" 
                  :section="footer.section" :endpoint="configUrl"></selector>              
                  <textbox v-else-if="option.type === 'text'" :configs="option":section="footer.section"
                  :endpoint="configUrl"></textbox>              
                  <category-select  v-else-if="option.type === 'category'" :categories="categories" :configs="option" :section="footer.section" :endpoint="configUrl"></category-select> 
                  <color-picker v-else-if="option.type === 'color'" :configs="option" :section="footer.section" :endpoint="configUrl"></color-picker>
                  <checkbox v-else-if="option.type === 'checkbox'" :configs="option" :section="footer.section"></checkbox>
                  <radio-button v-else-if="option.type === 'radio'" :configs="option" :section="footer.section" :endpoint="configUrl"></radio-button>
                  <menu-selector v-else-if="option.type === 'link_list'" :configs="option" :section="footer.section" :endpoint="configUrl"></menu-selector>
                  <custom-content v-else-if="option.type === 'html'" :configs="option" :section="footer.section"></custom-content>
                  
                </div>
                <div style="margin-bottom: 100px;"></div>
              </div>
            </div>

            <sections :theme="theme" :sections="sections" :settings="settings"></sections>

          </div>
        </div>
    </div>
  </div>
</div>
</template>
<script>
    import draggable from 'vuedraggable'
    import VueContentPlaceholders from 'vue-content-placeholders'
    import imagePicker from './components/image-picker'
    import selector from './components/selector'
    import textbox from './components/textbox'
    import categorySelect from './components/category-select'
    import productSelect from './components/product-select'
    import brandSelect from './components/brand-select'
    import colorPicker from './components/color-picker'
    import checkbox from './components/checkbox'
    import radioButton from './components/radio-button'
    import menuSelector from './components/menu-selector'
    import richtext from './components/richtext'
    import rangeSlider from './components/range-slider'
    import number from './components/number'
    import customContent from './components/custom-content'
    import fontPicker from './components/font-picker'
    import codemirror from './components/codemirror'
    import sections from './partials/Section'
    import RemoveSection from './partials/RemoveSection'
    import Reset from './partials/Reset'
    import frame from '../../frame'
    import bus from "../../bus"
    import queryString from 'query-string';
    import editorMixin from '../../editorMixin'

    export default {
      props: ['endpoint', 'placeholder', 'config-url', 'image-path', 'fonts', 'theme', 'resetUrl', 'overrideUrl'],
      mixins: [editorMixin],
      components: {
        draggable,
        VueContentPlaceholders,
        checkbox,
        selector,
        textbox,
        richtext,
        number,
        codemirror,
        sections,
        RemoveSection,
        Reset,
        brandSelect,
        'image-picker': imagePicker,
        'category-select':categorySelect,
        'product-select':productSelect,
        'color-picker':colorPicker,
        'radio-button':radioButton,
        'menu-selector':menuSelector,
        'range-slider':rangeSlider,
        'custom-content':customContent,
        'font-picker':fontPicker
      },
      data () {
        return {
          sectionSettings: [],
          currentUrl: '',
          footer: '',
          header: '',
          loading: true,
          home: true,
          ref_section: ''
        }
      },
      computed: {
        homePage: {
          get: function () {
            return this.home;
          },
          set: function (value) {
            this.home = value;
          }
        }
      },
      methods: {
        str_slug (value) {
          return _.kebabCase(value);
        }, 
        update (e) {
          var vm = this;
          this.settings.content_for_index = [];

          this.sectionSettings.forEach((value) => {
            if(value.type === 'content_for_index') {
              this.settings.content_for_index.push(value.section);
            }
          })

          axios.post(this.overrideUrl, {
            settings: this.settings
          }).then((response) => {
            $("#tpframe").attr('src', $("#tpframe").data('src'));
          }).catch((error) => {
            console.log('Something went wrong.')
          })          

        },
        openSettingView (section) {
          var timeout  = null;
          clearTimeout(timeout); 
          timeout = setTimeout(function () {
            bus.$emit('font.preview', section);
          }, 1000);
        },
        editingSection (section) {
          var timeout  = null;
          this.sectionSelect(section);
          $(frame).contents().find(`#shopbox-section-${section}`).get(0).scrollIntoView({ behavior: 'smooth', block: 'start'});

          clearTimeout(timeout); 
          timeout = setTimeout(function () {
            bus.$emit('menu.preview', section);
            bus.$emit('category.preview', section);
            bus.$emit('product.preview', section);
          }, 1000);
        },
        closeSectionPanel (section) {
          this.sectionDeselect(section)
        },
        toggleVisibility (section, visibility) {

          this.settings.sections[section].disabled = visibility;

          bus.$emit('preview.changes', {
            section_id: section,
            settings: this.settings
          });
         
        },
        addContent (content_type, default_blocks, section) {

          var index = Math.floor((Math.random() * 10000000000));

          if(content_type === 'image') {
            
            var block = default_blocks.find((block) => {
              return block.type === 'image';
            })

            this.$set(this.settings.sections[section].blocks, index.toString(), block);
            this.settings.sections[section].block_order.push(index);
   
            
          } else if (content_type === 'video') {

            var block = default_blocks.find((block) => {
              return block.type === 'video';
            })

            this.$set(this.settings.sections[section].blocks, index.toString(), block);
            this.settings.sections[section].block_order.push(index);
 
          } else if (content_type === 'product') {

            var block = default_blocks.find((block) => {
              return block.type === 'product';
            })

            if(Array.isArray(this.settings.sections[section].blocks)) {
              this.settings.sections[section].blocks = {};
            }

            this.$set(this.settings.sections[section].blocks, index.toString(), block);
            this.settings.sections[section].block_order.push(index.toString());

          }

          bus.$emit('preview.changes', {
            section_id: section,
            settings: this.settings.sections[section]
          });
          
        },
        removeContent (block, section) {

          this.settings.sections[section].blocks = Object.assign({}, this.settings.sections[section].blocks, delete this.settings.sections[section].blocks[block]);

          this.settings.sections[section].block_order.forEach((value, index) => {
            
            if(value == block) {
              this.settings.sections[section].block_order.splice(index, 1);
            }
            
          })

          bus.$emit('preview.changes', {
            section_id: section,
            settings: this.settings.sections[section]
          });
      
        },
        displayName (block, name = null, product = null) {

          if(block.type === 'product') {

            if(product) {
              return product.name.substr(0, 15) + '...';
            }

            return 'Product';

          } else if(block.type === 'text') {

            if(block.settings.title) {
              return block.settings.title.substr(0, 26);
            }

            return 'Text';
          } else if(block.type === 'image') {

            if(block.settings.title) {
              return block.settings.title.substr(0, 26);
            }

            return 'Image';

          } else if(block.settings && block.settings.title) {

            return block.settings.title.substr(0, 26);

          }

          return name.substr(0, 26);
        },
        displayImage (block, product) {

          if(block.type === 'image') {

            if(block.settings.image) {
              return `<img src="${this.shopbox.store_url + '/stores/' + this.shopbox.domain + '/img/' + block.settings.image}" alt="block image" width="40" class="img-fluid">`;
            }

            return '<i class="aapl-picture"></i>';

          } else if (block.type === 'product') {

            if(product && product.image) {
              return `<img src="${product.image}" alt="${product.name}" width="40" class="img-fluid">`;
            }

            return '<i class="aapl-tag"></i>'

          }
        },
        changeBlockOrder (section) {
          // frame.contentWindow.postMessage(this.settings, '*');
          bus.$emit('preview.changes', {
            section_id: section,
            settings: this.settings.sections[section]
          });
        },
        emitThemeEditorEvents (section) {
          this.sectionUnload(section);
          this.sectionLoad(section);
          this.sectionSelect(section);
        },
        sectionUnload (section) {
          var event = new CustomEvent("shopbox:section:unload", {
                        bubbles: true,
                        detail:{
                          sectionId: section
                        }
                      });
          var container = $(frame).contents().find(`#shopbox-section-${section}`).get(0);
          container.dispatchEvent(event);
        },
        sectionLoad (section) {
          var event = new CustomEvent("shopbox:section:load", {
                        bubbles: true,
                        detail:{
                          sectionId: section
                        }
                      });
          var container = $(frame).contents().find(`#shopbox-section-${section}`).get(0);
          container.dispatchEvent(event);
        },
        sectionSelect (section) {
          let event = new CustomEvent("shopbox:section:select", {
                        bubbles: true,
                        detail:{
                          sectionId: section
                        }
                      });
          var container = $(frame).contents().find(`#shopbox-section-${section}`).get(0);
          container.dispatchEvent(event);
        },
        sectionDeselect (section) {
          let event = new CustomEvent("shopbox:section:deselect", {
                        bubbles: true,
                        detail:{
                          sectionId: section
                        }
                      });
          var container = $(frame).contents().find(`#shopbox-section-${section}`).get(0);
          container.dispatchEvent(event);
        },
        blockSelect (section, block) {
          let event = new CustomEvent("shopbox:block:select", {
                        bubbles: true,
                        detail:{
                          blockId: block,
                          sectionId: section
                        }
                      });

          var container = $(frame).contents().find(`#shopbox-section-${section} [data-block='${block}']`).get(0);
          container.dispatchEvent(event);
        },
        blockDeselect (section, block) {
          let event = new CustomEvent("shopbox:block:deselect", {
                        bubbles: true,
                        detail:{
                          blockId: block,
                          sectionId: section
                        }
                      });

          var container = $(frame).contents().find(`#shopbox-section-${section} [data-block='${block}']`).get(0);
          container.dispatchEvent(event);
        },
        loadFrame() {
          var url = location.href.split('#');
          var src = $("#tpframe").data('src').split("?");

          if(url.length == 2) {
            var page = $(".page-list").find(`[href="${url[1]}"]`);
            var url = page.attr('href');
            $(".current__page").text(page.data('title'));

            if(url == '/' || typeof url == 'undefined') {
              this.homePage = true;
              $("#tpframe").attr('src', src.join("?"));
            } else {
              this.homePage = false;
              $("#tpframe").attr('src', src[0] + url + '?' + src[1]);
            }
          }
        },   
      },
      created () {
        this.loadFrame();
      },
      updated () {
    
        (function($) {
            'use strict';

            $('[data-navigate="view"]').each(function() {
              $(this).unbind();
            })

            var MobileView = function(element, options) {
                var self = this;
                self.options = $.extend(true, {}, $.fn.pgMobileViews.defaults, options);
                self.element = $(element);
                self.element.on('click', function(e) {
                    e.preventDefault();
                    var data = self.element.data();
                    var el = $(data.viewPort);
                    var toView = data.toggleView;
                    if (data.toggleView != null) {
                        el.children().last().children('.view').hide();
                        $(data.toggleView).show();
                    }
                    else{
                         toView = el.last();
                    }
                    el.toggleClass(data.viewAnimation);
                    self.options.onNavigate(toView, data.viewAnimation);
                    return false;
                })
                return this;
            };
            $.fn.pgMobileViews = function(options) {
                return new MobileView(this, options);
            };

            $.fn.pgMobileViews.defaults = {
                //Returns Target View & Animation Type
                onNavigate: function(view, animation) {}
            }
  
            $('[data-navigate="view"]').each(function() {
                var $mobileView = $(this)
                $mobileView.pgMobileViews();
            })

        })(window.jQuery);

      },
      mounted() {
        var vm = this;
        
        this.setShopbox(Shopbox);
        // this.getProducts();
        // this.getBrands();
        // this.getMenus();
        // this.getCategories();

        this.setSettings(window.Shopbox.config);
        this.setThemeSettings(window.Shopbox.themeSettings);
        this.setSections(window.Shopbox.sections);

        $("#tpframe").on('load', function () {
          vm.loading = false;
          var sections = JSON.parse($(frame).contents().find("#DesignModeThemeSections").text());
          vm.header = _.find(sections, ['type', 'header']);
          vm.footer = _.find(sections, ['type', 'footer']);
          vm.sectionSettings = sections;
        })
       
        this.currentUrl = $("#tpframe").attr('src');

        $(".list-view-wrapper").scrollbar();

        bus.$on('thumbnail.preview', (config) => {
           $(`[data-block-id="${config.blockId}"] .thumbnail__name`).text(this.displayName(config.block, '', config.product));
           $(`[data-block-id="${config.blockId}"] .thumbnail__preview`).html(this.displayImage(config.block, config.product));
        });

        bus.$on('section.added', (config) => {
          var section = this.settings.content_for_index[this.settings.content_for_index.length - 2];
          var elem = $(frame).contents().find(`#shopbox-section-${section}`);

          var formData = {
            section_id: config.section_id,
            settings: config.settings
          };

          axios.post(this.configUrl, formData).then((response) => {
            if(response.data.html) {
              $(response.data.html).insertAfter(elem);
              vm.sectionSettings.push(response.data.json);
              vm.sectionLoad(response.data.json.section);
              $(frame).contents().find(`#shopbox-section-${config.section_id}`).get(0).scrollIntoView({ behavior: 'auto', block: 'start'});
            } 
          }).catch((error) => {
            console.log('Something went wrong.')
          })

        })

        bus.$on('section.removed', (config) => {
          this.sectionSettings.forEach((content, index) => {
            
              if(content.section == config.section_id) {
                this.sectionSettings.splice(index, 1);
              }
              
          })

          $("#section-setting-list").last();
          $("#section-setting-list").toggleClass('push-parrallax');
          $(frame).contents().find(`#shopbox-section-${config.section_id}`).remove();

        })

        bus.$on('preview.changes', (config) => {

          var formData = {
            section_id: config.section_id,
            settings: config.settings
          };

          if(formData.section_id) {
            formData.settings = this.settings.sections[formData.section_id]
          }

          axios.post(this.configUrl, formData).then((response) => {
            if(response.data.html) {
              if($(frame).contents().find(`#shopbox-section-${formData.section_id}`).length) {
                $(frame).contents().find(`#shopbox-section-${formData.section_id}`).html(response.data.html);
                $(frame).contents().find(`#shopbox-section-${formData.section_id}`).get(0).scrollIntoView({ behavior: 'smooth', block: 'start'});
              } else {
                // var section = this.sectionSettings[_.findIndex(this.sectionSettings, ['section', config.section_id]) + 1]['section'];
                // var elem = $(frame).contents().find(`#shopbox-section-${section}`);
                // $(response.data.html).insertBefore(elem);
                // $(frame).contents().find(`#shopbox-section-${config.section_id}`).get(0).scrollIntoView({ behavior: 'smooth', block: 'start'});
              }
              vm.emitThemeEditorEvents(response.data.json.section);
            } else if(response.data.json && response.data.json.disabled) {
              vm.sectionUnload(response.data.json.section);
              // $(frame).contents().find(`#shopbox-section-${formData.section_id}`).remove();
              $(frame).contents().find(`#shopbox-section-${formData.section_id}`).html('');
            }

            if(response.data.json && response.data.json.content_for_index) {
              var index = _.findIndex(vm.sectionSettings, ['section', response.data.json.section]);
              vm.sectionSettings.splice(index, 1, response.data.json);
            }

            if(!formData.section_id) {
              $("#tpframe").attr('src', $("#tpframe").attr('src'));
            }

          }).catch((error) => {
            console.log('Something went wrong.')
          })

        })

        $(document).on('click', '.page-list a', function(e) {  

          e.preventDefault();
          var url = $(this).attr('href');
          var title = $(this).data('title');

          $(".current__page").text(title);

          var src = $("#tpframe").data('src').split("?");

          if(url == '/') {
            vm.homePage = true;
            history.pushState({}, "", " ");
            $("#tpframe").attr('src', src.join("?"));
          } else {
            vm.homePage = false;
            history.pushState({}, "", "#" + url);
            $("#tpframe").attr('src', src[0] + url + '?' + src[1]);
          }
          
        });

      }
    }
</script>


