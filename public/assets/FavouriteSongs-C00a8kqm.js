import{_ as c,f as n,r as i,c as e,F as l,k as p,h as d,o as s,l as u,p as g,e as m,b as _}from"./index-BCrCh3O-.js";import{S as h}from"./SongCard-nZr3fhIa.js";import"./PlaylistCard--LevWLeD.js";import"./not_liked-CjpSLIKd.js";import"./edit-CJGwvuct.js";const f={name:"FavouriteSongs",components:{SongCard:h},data(){return{songsCollection:null,userInfo:null}},mounted(){this.getFavouriteSongs(),this.getUserInfo()},methods:{getUserInfo(){n.get("http://music.local/api/auth/me").then(o=>{this.userInfo=o.data})},getFavouriteSongs(){n.get("http://music.local/api/favourite/songs").then(o=>{this.songsCollection=o.data})}}},v=o=>(g("data-v-ec3051f0"),o=o(),m(),o),S={class:"favourite-songs"},I=v(()=>_("div",{class:"title"},"Любимые треки",-1)),C={key:0,id:"songs",class:"songs"};function F(o,k,B,x,t,N){const a=i("song-card");return s(),e("div",S,[I,t.songsCollection?(s(),e("div",C,[(s(!0),e(l,null,p(t.songsCollection,r=>(s(),u(a,{songProps:r},null,8,["songProps"]))),256))])):d("",!0)])}const w=c(f,[["render",F],["__scopeId","data-v-ec3051f0"]]);export{w as default};