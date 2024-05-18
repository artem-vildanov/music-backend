import{_ as A,f as l,r as p,o as u,c as _,b as s,w as m,v as w,i as g,d as c,p as I,e as f,g as M,F as k,t as T,n as b,k as D,h as P,l as N}from"./index-BCrCh3O-.js";import{A as F}from"./AlbumCard-CWVu6pNK.js";import{S}from"./SelectGenre-bSW_1pll.js";import{_ as q,a as L}from"./not_liked-CjpSLIKd.js";import{_ as E}from"./edit-CJGwvuct.js";import{_ as U}from"./create-CM7SjTAk.js";const O={name:"CreateAlbum",components:{SelectGenre:S},data(){return{albumImage:null,albumName:"",albumPublishTime:null}},computed:{currentTime(){return new Date().toISOString().slice(0,16)}},watch:{},methods:{onMounted(){this.imgInputPreview(),this.$refs.selectGenreRef.getGenres()},publishTimeChangeHandler(){const t=document.getElementById("createPublishTime");this.albumPublishTime=t.value},imgInputPreview(){const t=document.querySelector(".create-album"),e=t.querySelector("#albumImageInput"),i=t.querySelector("#imagePreview");console.log(e),e.addEventListener("change",r=>{this.albumImage=r.target.files[0],i.src=URL.createObjectURL(this.albumImage)})},createAlbum(){this.albumImage&&this.albumName&&this.sendCreateAlbumRequest().then(this.displayCreatedAlbum).then(this.hideModal)},displayCreatedAlbum(t){const e=t.data;return new Promise(i=>{const r=`http://music.local/api/albums/${e}`;l.get(r).then(a=>{const o=a.data;this.$parent.$data.artistAlbums.push(o),i()})})},sendCreateAlbumRequest(){const t=this.makeFormData();return l.post("http://music.local/api/albums/create-album",t)},makeFormData(){const t=new FormData;return t.append("name",this.albumName),t.append("genreId",this.getSelectedGenreId()),t.append("photo",this.albumImage),t.append("publishTime",this.albumPublishTime),t},getSelectedGenreId(){const t=document.getElementsByName("genre");let e;for(var i=0;i<t.length;i++)if(t[i].checked){e=t[i].value;break}if(!e)throw Error("genre is not selected");return e},hideModal(){this.$parent.hideModal("createAlbumOverlay","createAlbumModal")()}}},y=t=>(I("data-v-1abd4188"),t=t(),f(),t),R={class:"create-album"},x=y(()=>s("div",{class:"title"}," Создать альбом ",-1)),G={class:"input-fields-container"},B={class:"select-genre-and-name"},V={class:"select-name"},j=y(()=>s("label",{for:"nameInput",class:"input-name-label"},"Выберите имя",-1)),H={class:"select-genre"},z={class:"select-image-and-publish-time"},J=y(()=>s("div",{class:"select-image"},[s("label",{for:"albumImageInput",class:"input-image-label"},"Выбрать обложку"),s("input",{id:"albumImageInput",type:"file",accept:"image/png"}),s("img",{id:"imagePreview",src:"",class:"image-preview"})],-1)),K={class:"select-publish-time"},Q=y(()=>s("label",{for:"createPublishTime"},"Выберите время публикации альбома: ",-1)),W=["value","min"];function X(t,e,i,r,a,o){const d=p("select-genre");return u(),_("div",R,[x,s("div",G,[s("div",B,[s("div",V,[j,m(s("input",{id:"nameInput","onUpdate:modelValue":e[0]||(e[0]=n=>a.albumName=n),type:"text",class:"input-name"},null,512),[[w,a.albumName]])]),s("div",H,[g(d,{ref:"selectGenreRef",preSelectedGenreId:null},null,512)])]),s("div",z,[J,s("div",K,[Q,s("input",{onChange:e[1]||(e[1]=(...n)=>o.publishTimeChangeHandler&&o.publishTimeChangeHandler(...n)),type:"datetime-local",id:"createPublishTime",value:o.currentTime,min:o.currentTime},null,40,W)])])]),s("div",{onClick:e[2]||(e[2]=c(n=>o.createAlbum(),["prevent"])),class:"submit-button"}," Создать ")])}const Y=A(O,[["render",X],["__scopeId","data-v-1abd4188"]]),Z={name:"DeleteArtist",props:["artist"],methods:{deleteArtist(){const t=`http://music.local/api/artists/${this.artist.id}/delete-artist`;l.delete(t).then(e=>{this.changeUserAccessToken(e.data.access_token),this.redirectToAccount()})},closeDeleteArtistModal(){this.$parent.hideDeleteArtistModal("deleteArtistModalOverlay","deleteArtistModal")()},redirectToAccount(){const t={name:"account.user"};this.$router.push(t)},changeUserAccessToken(t){localStorage.removeItem("access_token"),localStorage.setItem("access_token",t)}}},tt=t=>(I("data-v-7453f80c"),t=t(),f(),t),et={class:"confirmation-box"},st=tt(()=>s("div",{class:"title"},[M(" Вы уверены, что хотите"),s("br"),M(" удалить аккаунт артиста? ")],-1)),it={class:"buttons-container"};function at(t,e,i,r,a,o){return u(),_("div",et,[st,s("div",it,[s("div",{onClick:e[0]||(e[0]=c(d=>o.deleteArtist(),["prevent"])),class:"submit-button danger"}," Удалить "),s("div",{onClick:e[1]||(e[1]=c(d=>o.closeDeleteArtistModal(),["prevent"])),class:"submit-button"}," Отмена ")])])}const ot=A(Z,[["render",at],["__scopeId","data-v-7453f80c"]]),rt={name:"EditArtist",components:{SelectGenre:S,DeleteArtist:ot},data(){return{photoSrc:`http://music.local:9005/photo/${this.artist.photoPath}`,artistImage:null,oldArtistName:this.artist.name,newArtistName:""}},props:["artist"],methods:{onMounted(){this.imgInputPreview()},imgInputPreview(){const t=document.querySelector(".edit-artist"),e=t.querySelector("#imageInput"),i=t.querySelector("#imagePreview");e.addEventListener("change",r=>{this.artistImage=r.target.files[0],i.src=URL.createObjectURL(this.artistImage)})},editArtist(){(this.artistImage||this.newArtistName)&&this.editArtistName().then(this.editArtistImage).then(this.displayUpdatedArtist).then(this.hideModal)},editArtistName(){return new Promise(t=>{if(this.newArtistName){const e=`http://music.local/api/artists/${this.artist.id}/update-artist-name`,i={name:this.newArtistName,genreId:this.selectedGenre};l.post(e,i).then(t)}else t()})},editArtistImage(){return new Promise(t=>{if(this.artistImage){const e=`http://music.local/api/artists/${this.artist.id}/update-artist-photo`,i=this.makeImageFormData();l.post(e,i).then(t)}else t()})},makeImageFormData(){const t=new FormData;return t.append("photo",this.artistImage),t},displayUpdatedArtist(){return new Promise(t=>{this.getUpdatedArtist().then(e=>{const i=e.data;this.$parent.$data.artist=i,this.$parent.$data.photoSrc=`http://music.local:9005/photo/${i.photoPath}`,t()})})},getUpdatedArtist(){const t=`http://music.local/api/artists/${this.artist.id}`;return l.get(t)},hideModal(){this.$parent.hideModal("editArtistModalOverlay","editArtistModal")()},deleteArtist(){this.openDeleteArtistModal("deleteArtistModalOverlay","deleteArtistModal")},openDeleteArtistModal(t,e){const i=document.querySelector(`#${e}`);i.style.visibility="visible",i.style.opacity="1",setTimeout(()=>{this.overlayClickListener(t,e)},500)},overlayClickListener(t,e){document.querySelector(`#${t}`).addEventListener("click",this.hideDeleteArtistModal(t,e))},hideDeleteArtistModal(t,e){return()=>{const i=document.querySelector(`#${t}`),r=document.querySelector(`#${e}`);r.style.visibility="hidden",r.style.opacity="0",i.removeEventListener("click",this.hideDeleteArtistModal(t,e))}}}},v=t=>(I("data-v-07ae800c"),t=t(),f(),t),lt={class:"edit-artist"},nt=v(()=>s("div",{class:"title"}," Изменить артиста ",-1)),ct={class:"input-fields-container"},dt={class:"select-name"},mt=v(()=>s("label",{for:"nameInput",class:"input-name-label"},"Выберите имя",-1)),ut=["placeholder"],ht={class:"select-image"},pt=v(()=>s("label",{for:"imageInput",class:"input-image-label"},"Выбрать обложку",-1)),_t=v(()=>s("input",{id:"imageInput",type:"file",accept:"image/png"},null,-1)),vt=["src"],bt={class:"buttons-container"},gt={class:"modal",id:"deleteArtistModal"},At=v(()=>s("div",{class:"modal-overlay",id:"deleteArtistModalOverlay"},null,-1)),It={class:"modal-window"};function ft(t,e,i,r,a,o){const d=p("delete-artist");return u(),_(k,null,[s("div",lt,[nt,s("div",ct,[s("div",dt,[mt,m(s("input",{id:"nameInput",placeholder:a.oldArtistName,"onUpdate:modelValue":e[0]||(e[0]=n=>a.newArtistName=n),type:"text",class:"input-name"},null,8,ut),[[w,a.newArtistName]])]),s("div",ht,[pt,_t,s("img",{id:"imagePreview",src:a.photoSrc,class:"image-preview"},null,8,vt)])]),s("div",bt,[s("div",{onClick:e[1]||(e[1]=c(n=>o.editArtist(),["prevent"])),class:"submit-button"}," Изменить "),s("div",{onClick:e[2]||(e[2]=c(n=>o.deleteArtist(),["prevent"])),class:"submit-button danger"}," Удалить ")])]),s("div",gt,[At,s("div",It,[g(d,{artist:i.artist},null,8,["artist"])])])],64)}const yt=A(rt,[["render",ft],["__scopeId","data-v-07ae800c"]]),$t={name:"Artist",components:{AlbumCard:F,CreateAlbum:Y,EditArtist:yt},data(){return{artistId:this.$route.params.id,artist:null,artistAlbums:null,photoSrc:null,userInfo:null}},mounted(){this.getArtist(),this.getArtistAlbums(),this.getUserInfo()},methods:{getArtist(){l.get(`http://music.local/api/artists/${this.artistId}`).then(t=>{this.artist=t.data,this.photoSrc=`http://music.local:9005/photo/${this.artist.photoPath}`})},getArtistAlbums(){l.get(`http://music.local/api/albums/created-by-artist/${this.artistId}`).then(t=>{this.artistAlbums=t.data})},getUserInfo(){l.get("http://music.local/api/auth/me").then(t=>{this.userInfo=t.data})},openModalEditArtist(){this.$refs.editArtistRef.onMounted(),this.openModal("editArtistModalOverlay","editArtistModal")},openModalCreateAlbum(){this.$refs.createAlbumRef.onMounted(),this.openModal("createAlbumOverlay","createAlbumModal")},openModal(t,e){const i=document.querySelector(`#${e}`);i.style.visibility="visible",i.style.opacity="1",setTimeout(()=>{this.overlayClickListener(t,e)},500)},overlayClickListener(t,e){document.querySelector(`#${t}`).addEventListener("click",this.hideModal(t,e))},hideModal(t,e){return()=>{const i=document.querySelector(`#${t}`),r=document.querySelector(`#${e}`);r.style.visibility="hidden",r.style.opacity="0",i.removeEventListener("click",this.hideModal(t,e))}},addToFavourites(){l.put(`http://music.local/api/favourite/artists/add-to-favourites/${this.artist.id}`),this.artist.isFavourite=!0},removeFromFavourites(){l.put(`http://music.local/api/favourite/artists/delete-from-favourites/${this.artist.id}`),this.artist.isFavourite=!1}}},$=t=>(I("data-v-7ca95de7"),t=t(),f(),t),Mt={key:0,class:"artist-container"},wt={class:"dashboard"},kt={class:"photo-container"},St=["src"],Ct={class:"info-container"},Tt={class:"info-container__artist-name"},Dt={class:"info-container__actions-container"},Pt={class:"actions-container__action"},Nt=$(()=>s("img",{class:"icon select-none",src:E},null,-1)),Ft=[Nt],qt=$(()=>s("img",{class:"icon select-none",src:U},null,-1)),Lt=[qt],Et={class:"artist-content"},Ut={class:"albums"},Ot={class:"modal",id:"editArtistModal"},Rt=$(()=>s("div",{class:"modal-overlay",id:"editArtistModalOverlay"},null,-1)),xt={class:"modal-window"},Gt={class:"modal",id:"createAlbumModal"},Bt=$(()=>s("div",{class:"modal-overlay",id:"createAlbumOverlay"},null,-1)),Vt={class:"modal-window"};function jt(t,e,i,r,a,o){const d=p("album-card"),n=p("edit-artist"),C=p("create-album");return a.artist?(u(),_("div",Mt,[s("div",wt,[s("div",kt,[s("img",{class:"artist-photo select-none",src:a.photoSrc},null,8,St)]),s("div",Ct,[s("div",Tt,T(a.artist.name),1),s("div",Dt,[s("div",Pt,[m(s("img",{onClick:e[0]||(e[0]=c(h=>o.removeFromFavourites(),["prevent"])),class:"icon select-none",src:q},null,512),[[b,a.artist.isFavourite]]),m(s("img",{onClick:e[1]||(e[1]=c(h=>o.addToFavourites(),["prevent"])),class:"icon select-none",src:L},null,512),[[b,!a.artist.isFavourite]])]),m(s("div",{onClick:e[2]||(e[2]=c(h=>o.openModalEditArtist(),["prevent"])),class:"actions-container__action"},Ft,512),[[b,a.userInfo&&a.artist.id===a.userInfo.artistId]]),m(s("div",{onClick:e[3]||(e[3]=c(h=>o.openModalCreateAlbum(),["prevent"])),class:"actions-container__action"},Lt,512),[[b,a.userInfo&&a.artist.id===a.userInfo.artistId]])])])]),s("div",Et,[s("div",Ut,[(u(!0),_(k,null,D(a.artistAlbums,h=>(u(),N(d,{album:h},null,8,["album"]))),256))])]),s("div",Ot,[Rt,s("div",xt,[g(n,{ref:"editArtistRef",artist:a.artist},null,8,["artist"])])]),s("div",Gt,[Bt,s("div",Vt,[g(C,{ref:"createAlbumRef"},null,512)])])])):P("",!0)}const Xt=A($t,[["render",jt],["__scopeId","data-v-7ca95de7"]]);export{Xt as default};
