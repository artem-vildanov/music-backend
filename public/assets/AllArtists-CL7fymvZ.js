import{_ as n,f as i,r as l,c as r,F as c,k as p,o as t,l as d}from"./index-BCrCh3O-.js";import{A as _}from"./ArtistCard-OQATXy4c.js";import"./not_liked-CjpSLIKd.js";const m={name:"AllArtists",components:{ArtistCard:_},data(){return{artistsGroup:null}},mounted(){this.getAllArtists()},methods:{getAllArtists(){i.get("http://music.local/api/artists/all").then(s=>{this.artistsGroup=s.data})}}},u={class:"all-artists"};function A(s,f,h,g,a,k){const e=l("artist-card");return t(),r("div",u,[(t(!0),r(c,null,p(a.artistsGroup,o=>(t(),d(e,{artist:o},null,8,["artist"]))),256))])}const G=n(m,[["render",A],["__scopeId","data-v-94d573be"]]);export{G as default};
