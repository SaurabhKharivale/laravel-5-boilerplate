webpackJsonp([2],{0:function(t,e,a){t.exports=a("KYYh")},"5R1j":function(t,e,a){var n=a("VU/8")(a("ZG12"),a("nRtg"),null,null);t.exports=n.exports},"8fKR":function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("button",{staticClass:"create-new-admin button",on:{click:function(e){t.creating=!0}}},[t._v("Create new admin")]),t._v(" "),a("modal",{attrs:{active:t.creating},on:{hide:function(e){t.creating=!1}}},[a("form",{staticClass:"form"},[a("div",{staticClass:"field"},[a("h1",{staticClass:"title"},[t._v("New admin details")])]),t._v(" "),a("div",{staticClass:"field"},[a("p",{staticClass:"control"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.first_name,expression:"form.first_name"}],staticClass:"input",class:{"is-danger":t.form.errors.has("first_name")},attrs:{name:"first_name",placeholder:"First name",autofocus:""},domProps:{value:t.form.first_name},on:{input:function(e){e.target.composing||(t.form.first_name=e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"field"},[a("p",{staticClass:"control"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.last_name,expression:"form.last_name"}],staticClass:"input",class:{"is-danger":t.form.errors.has("last_name")},attrs:{name:"last_name",placeholder:"Last name"},domProps:{value:t.form.last_name},on:{input:function(e){e.target.composing||(t.form.last_name=e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"field"},[a("p",{staticClass:"conctrol"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.email,expression:"form.email"}],staticClass:"input",class:{"is-danger":t.form.errors.has("email")},attrs:{name:"email",placeholder:"Email"},domProps:{value:t.form.email},on:{input:function(e){e.target.composing||(t.form.email=e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"field"},[a("p",{staticClass:"control"},[a("button",{staticClass:"save-admin button is-primary",on:{click:function(e){e.preventDefault(),t.submit(e)}}},[t._v("Save")])])])])])],1)},staticRenderFns:[]}},Co6O:function(t,e,a){var n=a("VU/8")(a("QXEd"),a("8fKR"),null,null);t.exports=n.exports},KYYh:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=a("5R1j"),s=a.n(n),r=a("Co6O"),i=a.n(r);new Vue({el:"#admin",components:{AdminsList:s.a,CreateAdmin:i.a}})},QXEd:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{creating:!1,form:new Form({first_name:"",last_name:"",email:""})}},methods:{submit:function(){this.creating=!1,this.form.submit("post","/api/admin").then(function(t){return flash(t.message,t.type)}).catch(function(t){return flash("Admin creation failed. Please Try again.","danger")})}}}},"VU/8":function(t,e){t.exports=function(t,e,a,n){var s,r=t=t||{},i=typeof t.default;"object"!==i&&"function"!==i||(s=t,r=t.default);var o="function"==typeof r?r.options:r;if(e&&(o.render=e.render,o.staticRenderFns=e.staticRenderFns),a&&(o._scopeId=a),n){var l=Object.create(o.computed||null);Object.keys(n).forEach(function(t){var e=n[t];l[t]=function(){return e}}),o.computed=l}return{esModule:s,exports:r,options:o}}},ZG12:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={mounted:function(){this.getAll()},data:function(){return{admins:[],error:!1}},methods:{getAll:function(){var t=this;axios.get("/api/admin").then(function(e){return t.admins=e.data.admins}).catch(function(e){return t.error=!0})}}}},nRtg:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("table",{staticClass:"table"},[t._m(0),t._v(" "),a("tbody",t._l(t.admins,function(e){return a("tr",[a("td",[t._v(t._s(e.first_name)+" "+t._s(e.last_name))]),t._v(" "),a("td",[t._v(t._s(e.email))])])}))]),t._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:t.error,expression:"error"}]},[t._v("Unable to load admins data")])])},staticRenderFns:[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("thead",[a("tr",[a("th",[t._v("Name")]),t._v(" "),a("th",[t._v("Email")])])])}]}}},[0]);