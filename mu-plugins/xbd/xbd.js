(function(){
var R=document.getElementById('xbd-root');if(!R)return;R.classList.add('xb-js');
var io=('IntersectionObserver' in window)?new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.12,rootMargin:'0px 0px -40px 0px'}):null;
R.querySelectorAll('.xb-rv').forEach(function(e,i){e.style.transitionDelay=((i%4)*70)+'ms';io?io.observe(e):e.classList.add('in')});
function count(el){var n=+el.dataset.n,s=el.dataset.s||'',t0=null;function f(t){if(!t0)t0=t;var p=Math.min((t-t0)/1400,1);el.textContent=Math.round(n*(1-Math.pow(1-p,3)))+s;if(p<1)requestAnimationFrame(f)}requestAnimationFrame(f)}
var cio=('IntersectionObserver' in window)?new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){count(e.target);cio.unobserve(e.target)}})},{threshold:.6}):null;
R.querySelectorAll('[data-n]').forEach(function(e){cio?cio.observe(e):(e.textContent=e.dataset.n+(e.dataset.s||''))});
R.addEventListener('pointermove',function(ev){var g=ev.target.closest&&ev.target.closest('.xb-gl');if(!g)return;var r=g.getBoundingClientRect();g.style.setProperty('--mx',(ev.clientX-r.left)+'px');g.style.setProperty('--my',(ev.clientY-r.top)+'px')},{passive:true});
var tb=R.querySelectorAll('.xb-tabs button'),pk=R.querySelectorAll('.xb-pk');
tb.forEach(function(b){b.addEventListener('click',function(){tb.forEach(function(x){x.classList.toggle('on',x===b)});pk.forEach(function(p){p.classList.toggle('on',p.dataset.p===b.dataset.t)})})});
/* tilt fan */
var fw=R.querySelector('.xb-fanwrap'),fan=R.querySelector('.xb-fan');
if(fw&&fan&&matchMedia('(hover:hover)').matches){fw.addEventListener('pointermove',function(e){var r=fw.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;fan.style.transform='rotateX('+(8-y*10)+'deg) rotateY('+(-18+x*14)+'deg)'});fw.addEventListener('pointerleave',function(){fan.style.transform=''})}
/* contact form */
var fwa=document.getElementById('xf-wa');
if(fwa){
function build(){var n=document.getElementById('xf-n').value.trim(),c=document.getElementById('xf-c').value.trim(),m=document.getElementById('xf-m').value.trim(),s=document.getElementById('xf-s').value,b=document.getElementById('xf-b').value,msg=document.getElementById('xf-msg');
 if(!n||!m){msg.textContent='Please add your name and a short message.';return null}msg.textContent='';
 return 'Hi XBD, I’m '+n+'.\nService: '+s+'\nBudget: '+b+(c?'\nContact: '+c:'')+'\n\n'+m}
fwa.addEventListener('click',function(){var t=build();if(t)window.open('https://wa.me/8801833375133?text='+encodeURIComponent(t),'_blank','noopener')});
document.getElementById('xf-em').addEventListener('click',function(){var t=build();if(t)window.location.href='mailto:info@x-bd.com?subject='+encodeURIComponent('Project enquiry from the XBD website')+'&body='+encodeURIComponent(t)});
}
function esc(s){var d=document.createElement('div');d.textContent=s;return d.innerHTML}
/* posts (home teaser + blog page) */
var box=document.getElementById('xb-posts');
if(box){var n=+box.dataset.n||3,full=box.dataset.full==='1',sec=document.getElementById('blog'),empty=document.getElementById('xb-blog-empty');
fetch('/wp-json/wp/v2/posts?per_page='+n+'&_embed=wp:featuredmedia&_fields=id,date,link,title,excerpt,_links,_embedded').then(function(r){return r.ok?r.json():[]}).then(function(list){
 if(!Array.isArray(list)||!list.length){if(empty)empty.style.display='';return}
 box.innerHTML=list.map(function(p){var m=p._embedded&&p._embedded['wp:featuredmedia']&&p._embedded['wp:featuredmedia'][0],img=m&&((m.media_details&&m.media_details.sizes&&m.media_details.sizes.medium_large&&m.media_details.sizes.medium_large.source_url)||m.source_url),ex=(p.excerpt&&p.excerpt.rendered||'').replace(/<[^>]+>/g,'').replace(/&hellip;|\[&hellip;\]/g,'…').trim().slice(0,130),d=new Date(p.date).toLocaleDateString('en-GB',{day:'numeric',month:'short',year:'numeric'});
 return '<article class="xb-post xb-gl"><a class="xb-ph'+(img?'':' ph')+'" href="'+p.link+'">'+(img?'<img src="'+img+'" alt="" loading="lazy">':'')+'</a><div class="xb-cb"><small>'+d+'</small><h3><a href="'+p.link+'" style="color:#fff">'+p.title.rendered+'</a></h3><p>'+esc(ex)+(ex.length>=130?'…':'')+'</p><a href="'+p.link+'">Read article →</a></div></article>'}).join('');
 if(sec)sec.style.display='';
}).catch(function(){if(empty)empty.style.display=''})}
/* portfolio */
var grid=document.getElementById('xb-pgrid');
if(grid&&window.XB_PROJ){var P=window.XB_PROJ,F=document.getElementById('xb-filters'),M=document.getElementById('xb-modal');
 var cats=[['all','All work']].concat(window.XB_CATS||[]);
 if(!F.children.length)F.innerHTML=cats.map(function(c,i){var k=c[0],cnt=k==='all'?P.length:P.filter(function(p){return p.cat.indexOf(k)>-1}).length;return cnt?'<button class="xb-fb2'+(i?'':' on')+'" data-f="'+k+'">'+c[1]+' <span>'+cnt+'</span></button>':''}).join('');
 if(!grid.children.length)grid.innerHTML=P.map(function(p,i){return '<button class="xb-pcard xb-gl xb-rv" data-i="'+i+'" data-c="'+p.cat.join(' ')+'" type="button"><div class="xb-ph"><span>'+esc(p.where)+'</span><img src="'+p.img+'" width="1200" height="750" alt="'+esc(p.name)+' website" loading="lazy"></div><div class="xb-cb"><small>'+esc(p.tags)+'</small><h3>'+esc(p.name)+'</h3><p>'+esc(p.sum)+'</p><span class="more">View details →</span></div></button>'}).join('');
 grid.querySelectorAll('.xb-rv').forEach(function(e){e.classList.add('in')});
 F.addEventListener('click',function(e){var b=e.target.closest('.xb-fb2');if(!b)return;F.querySelectorAll('.xb-fb2').forEach(function(x){x.classList.toggle('on',x===b)});var f=b.dataset.f;grid.querySelectorAll('.xb-pcard').forEach(function(c){c.classList.toggle('hide',f!=='all'&&(' '+c.dataset.c+' ').indexOf(' '+f+' ')<0)})});
 function open(i){var p=P[i];M.querySelector('.xb-mimg').innerHTML='<img src="'+p.img+'" alt="'+esc(p.name)+' website">';
  M.querySelector('.xb-mb').innerHTML='<small>'+esc(p.where)+' · '+esc(p.tags)+'</small><h3>'+esc(p.name)+'</h3><p>'+esc(p.sum)+'</p><h5>What we did</h5><ul class="xb-ck">'+p.did.map(function(x){return '<li>'+esc(x)+'</li>'}).join('')+'</ul><h5>Outcome</h5><p>'+esc(p.out)+'</p><a class="xb-btn pri" href="'+p.url+'" target="_blank" rel="noopener">Visit '+esc(p.host)+' ↗</a>';
  M.classList.add('on');document.documentElement.style.overflow='hidden'}
 function close(){M.classList.remove('on');document.documentElement.style.overflow=''}
 grid.addEventListener('click',function(e){var c=e.target.closest('.xb-pcard');if(c)open(+c.dataset.i)});
 M.addEventListener('click',function(e){if(e.target===M||e.target.classList.contains('xb-x'))close()});
 document.addEventListener('keydown',function(e){if(e.key==='Escape')close()});
}
})();
