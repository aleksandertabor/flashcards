(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{222:function(e,r,s){"use strict";s.r(r);var a=s(3),t=s.n(a),n=s(16);function i(e,r,s,a,t,n,i){try{var o=e[n](i),l=o.value}catch(e){return void s(e)}o.done?r(l):Promise.resolve(l).then(a,t)}var o={mixins:[s(60).a],props:{userData:Object},data:function(){return{password:"",password_confirmation:"",showPassword:!1,isEditing:null}},methods:{save:function(){var e,r=this;return(e=t.a.mark((function e(){var s;return t.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r.loading=!0,r.errors=null,r.error=!1,r.success=!1,e.prev=4,e.next=7,r.$store.dispatch("profile/editProfile",{id:r.userData.id,username:r.userData.username,email:r.userData.email,password:r.password,password_confirmation:r.password_confirmation});case 7:s=e.sent.username,r.isEditing=!r.isEditing,r.success=!0,s!==r.$route.params.username&&r.$router.push({name:"profile",params:{username:s}}),e.next=17;break;case 13:e.prev=13,e.t0=e.catch(4),Object(n.b)(e.t0)&&(r.errors=Object(n.b)(e.t0)),Object(n.a)(e.t0)&&(r.error=Object(n.a)(e.t0));case 17:r.loading=!1;case 18:case"end":return e.stop()}}),e,null,[[4,13]])})),function(){var r=this,s=arguments;return new Promise((function(a,t){var n=e.apply(r,s);function o(e){i(n,a,t,o,l,"next",e)}function l(e){i(n,a,t,o,l,"throw",e)}o(void 0)}))})()}},computed:{isDisable:function(){return this.loading||!this.isEditing}}},l=s(12),u=s(14),c=s.n(u),d=s(567),p=s(127),m=s(568),v=s(126),f=s(571),b=s(41),w=Object(l.a)(o,(function(){var e=this,r=e.$createElement,s=e._self._c||r;return s("div",[s("v-form",{ref:"form",attrs:{"lazy-validation":""}},[s("v-btn",{attrs:{color:"purple darken-3",outlined:"",tile:""},on:{click:function(r){e.isEditing=!e.isEditing}}},[e.isEditing?s("v-icon",{attrs:{left:""}},[e._v("mdi-close")]):s("v-icon",{attrs:{left:""}},[e._v("mdi-pencil")]),e._v("Edit\n    ")],1),e._v(" "),s("div",{staticClass:"py-5 px-0"},[e.success?s("v-alert",{attrs:{type:"success",dismissible:""}},[e._v("Your account has saved.")]):e._e(),e._v(" "),e.error?s("v-alert",{attrs:{type:"error",dismissible:""}},[e._v("Fill your data correctly.")]):e._e()],1),e._v(" "),s("v-snackbar",{attrs:{timeout:2e3,bottom:"",left:""},model:{value:e.success,callback:function(r){e.success=r},expression:"success"}},[e._v("Your profile has been updated.")]),e._v(" "),s("v-snackbar",{attrs:{timeout:2e3,bottom:"",left:""},model:{value:e.error,callback:function(r){e.error=r},expression:"error"}},[e._v("Fill your data correctly.")]),e._v(" "),s("v-text-field",{attrs:{"prepend-icon":"mdi-account",label:"Username",rules:[e.rules.required],"error-messages":e.errorFor("username"),loading:e.loading,disabled:e.isDisable},on:{input:function(r){return e.$emit("input",e.userData)},keyup:function(r){return!r.type.indexOf("key")&&e._k(r.keyCode,"enter",13,r.key,"Enter")?null:e.save(r)}},model:{value:e.userData.username,callback:function(r){e.$set(e.userData,"username",r)},expression:"userData.username"}}),e._v(" "),s("v-text-field",{attrs:{"prepend-icon":"mdi-at",label:"Email",rules:[e.rules.required],"error-messages":e.errorFor("email"),loading:e.loading,disabled:e.isDisable},on:{input:function(r){return e.$emit("input",e.userData)},keyup:function(r){return!r.type.indexOf("key")&&e._k(r.keyCode,"enter",13,r.key,"Enter")?null:e.save(r)}},model:{value:e.userData.email,callback:function(r){e.$set(e.userData,"email",r)},expression:"userData.email"}}),e._v(" "),s("v-text-field",{attrs:{"prepend-icon":"mdi-lock",rules:[e.rules.min],"error-messages":e.errorFor("password"),type:e.showPassword?"text":"password",name:"input-10-1",label:"New Password",counter:"",loading:e.loading,disabled:e.isDisable},on:{keyup:function(r){return!r.type.indexOf("key")&&e._k(r.keyCode,"enter",13,r.key,"Enter")?null:e.save(r)}},model:{value:e.password,callback:function(r){e.password=r},expression:"password"}}),e._v(" "),s("v-text-field",{attrs:{"prepend-icon":"mdi-lock",rules:[e.rules.min],"error-messages":e.errorFor("password_confirmation"),name:"input-10-1",type:e.showPassword?"text":"password",label:"New Password confirmation",counter:"",loading:e.loading,disabled:e.isDisable},on:{keyup:function(r){return!r.type.indexOf("key")&&e._k(r.keyCode,"enter",13,r.key,"Enter")?null:e.save(r)}},model:{value:e.password_confirmation,callback:function(r){e.password_confirmation=r},expression:"password_confirmation"}}),e._v(" "),e.isEditing?s("v-btn",{staticClass:"ma-2",attrs:{disabled:e.isDisable,color:"indigo",dark:""},on:{click:function(r){e.showPassword=!e.showPassword}}},[s("v-icon",[e._v(e._s(e.showPassword?"mdi-eye":"mdi-eye-off"))])],1):e._e(),e._v(" "),s("v-btn",{staticClass:"mr-4",attrs:{disabled:e.isDisable,color:"success"},on:{click:e.save}},[e._v("Save profile")])],1)],1)}),[],!1,null,null,null);r.default=w.exports;c()(w,{VAlert:d.a,VBtn:p.a,VForm:m.a,VIcon:v.a,VSnackbar:f.a,VTextField:b.a})}}]);
//# sourceMappingURL=1.chunk.js.map