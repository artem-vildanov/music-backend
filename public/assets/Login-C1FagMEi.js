import{_ as p,m as c,a as d,c as m,b as t,w as i,v as r,d as u,o as f,p as _,e as h}from"./index-BCrCh3O-.js";const w={name:"login",data(){return{email:null,password:null}},methods:{...c(["fetchUserInfo"]),async login(){const o="http://music.local/api/auth/login",s={email:this.email,password:this.password},l=await d.post(o,s);localStorage.setItem("access_token",l.data.access_token),this.$router.push({name:"account.user"})}}},b=o=>(_("data-v-bcb2e2df"),o=o(),h(),o),g={class:"login-wrapper"},v={class:"login-form"},I=b(()=>t("h1",null,"Login",-1));function k(o,s,l,x,a,n){return f(),m("div",g,[t("div",v,[I,i(t("input",{"onUpdate:modelValue":s[0]||(s[0]=e=>a.email=e),type:"email",class:"form-control mb-3 mt-3",placeholder:"email"},null,512),[[r,a.email]]),i(t("input",{"onUpdate:modelValue":s[1]||(s[1]=e=>a.password=e),type:"password",class:"form-control mb-3",placeholder:"password"},null,512),[[r,a.password]]),t("input",{onClick:s[2]||(s[2]=u((...e)=>n.login&&n.login(...e),["prevent"])),type:"submit",value:"Submit",class:"btn btn-outline-primary mb-3"})])])}const S=p(w,[["render",k],["__scopeId","data-v-bcb2e2df"]]);export{S as default};
