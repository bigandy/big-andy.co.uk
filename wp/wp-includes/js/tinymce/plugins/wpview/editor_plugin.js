(function(){tinymce.create("tinymce.plugins.wpView",{init:function(c,b){var a=this;if(typeof wp==="undefined"||!wp.mce){return}c.onPreInit.add(function(d){d.schema.addValidElements("div[*],span[*]")});c.onBeforeSetContent.add(function(d,e){if(!e.content){return}e.content=wp.mce.view.toViews(e.content)});c.onSetContent.add(function(d,e){wp.mce.view.render(d.getDoc())});c.onInit.add(function(d){d.selection.onSetContent.add(function(e,g){if(!g.context){return}var f=e.getNode();if(!f.innerHTML){return}f.innerHTML=wp.mce.view.toViews(f.innerHTML);wp.mce.view.render(f)})});c.onPostProcess.add(function(d,e){if((!e.get&&!e.save)||!e.content){return}e.content=wp.mce.view.toText(e.content)})},getInfo:function(){return{longname:"WordPress Views",author:"WordPress",authorurl:"http://wordpress.org",infourl:"http://wordpress.org",version:"1.0"}}});tinymce.PluginManager.add("wpview",tinymce.plugins.wpView)})();