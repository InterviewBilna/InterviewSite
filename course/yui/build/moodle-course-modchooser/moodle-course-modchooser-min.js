YUI.add("moodle-course-modchooser",function(e,t){var n={PAGECONTENT:"div#page-content",SECTION:"li.section",SECTIONMODCHOOSER:"span.section-modchooser-link",SITEMENU:"div.block_site_main_menu",SITETOPIC:"div.sitetopic"},r="course-modchooser",i=function(){i.superclass.constructor.apply(this,arguments)};e.extend(i,M.core.chooserdialogue,{sectionid:null,initializer:function(){var t=e.one(".chooserdialoguebody"),n=e.one(".choosertitle"),r={};this.setup_chooser_dialogue(t,n,r),this.setup_for_section(),M.course.coursebase.register_module(this),e.all(".block_settings #settingsnav .type_course .modchoosertoggle a").on("click",this.toggle_mod_chooser,this)},setup_for_section:function(t){t||(t=n.PAGECONTENT),e.one(t).all(n.SITETOPIC).each(function(e){this._setup_for_section(e)},this),e.one(t).all(n.SECTION).each(function(e){this._setup_for_section(e)},this),e.one(t).all(n.SITEMENU).each(function(e){this._setup_for_section(e)},this)},_setup_for_section:function(t){var r=t.one(n.SECTIONMODCHOOSER);if(!r)return;var i=e.Node.create("<a href='#' />");r.get("children").each(function(e){i.appendChild(e)}),r.insertBefore(i),i.on("click",this.display_mod_chooser,this)},display_mod_chooser:function(e){if(e.target.ancestor(n.SITETOPIC))this.sectionid=1;else if(e.target.ancestor(n.SECTION)){var t=e.target.ancestor(n.SECTION);this.sectionid=t.get("id").replace("section-","")}else e.target.ancestor(n.SITEMENU)&&(this.sectionid=0);this.display_chooser(e)},toggle_mod_chooser:function(t){var n=e.all("div.addresourcemodchooser"),r=e.all("div.addresourcedropdown");if(n.size()===0)return;var i=e.one(".block_settings #settingsnav .type_course .modchoosertoggle a"),s=i.get("lastChild"),o;n.item(0).hasClass("visibleifjs")?(o=0,n.removeClass("visibleifjs").addClass("hiddenifjs"),r.addClass("visibleifjs").removeClass("hiddenifjs"),s.set("data",M.util.get_string("modchooserenable","moodle")),i.set("href",i.get("href").replace("off","on"))):(o=1,n.addClass("visibleifjs").removeClass("hiddenifjs"),r.removeClass("visibleifjs").addClass("hiddenifjs"),s.set("data",M.util.get_string("modchooserdisable","moodle")),i.set("href",i.get("href").replace("on","off"))),M.util.set_user_preference("usemodchooser",o),t.preventDefault()},option_selected:function(e){this.hiddenRadioValue.setAttrs({name:"jump",value:e.get("value")+"&section="+this.sectionid})}},{NAME:r,ATTRS:{maxheight:{value:800}}}),M.course=M.course||{},M.course.init_chooser=function(e){return new i(e)}},"@VERSION@",{requires:["moodle-core-chooserdialogue","moodle-course-coursebase"]});