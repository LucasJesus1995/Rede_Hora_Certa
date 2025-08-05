<?php if(ENV_SISTEMA == "PRODUCAO") {?>

<!-- Start of ciesglobal Zendesk Widget script -->
<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var e=this.createElement("script");n&&(this.domain=n),e.id="js-iframe-async",e.src="https://assets.zendesk.com/embeddable_framework/main.js",this.t=+new Date,this.zendeskHost="ciesglobal.zendesk.com",this.zEQueue=a,this.body.appendChild(e)},o.write('<body onload="document._l();">'),o.close()}();
    /*]]>*/</script>
<!-- End of ciesglobal Zendesk Widget script -->

<script>
    function fncZopim(){
        $zopim.livechat.setName('<?php echo \App\Http\Helpers\Util::getUserName(); ?>');
        $zopim.livechat.setEmail('<?php echo \App\Http\Helpers\Util::getUserEmail(); ?>');
        $zopim.livechat.setLanguage("pt");
        $zopim.livechat.addTags('ciesglobal');
        $zopim.livechat.window.setTitle('CIES');
    }
    timeout = setTimeout(function () {
        timeout = false;
        fncZopim();
    }, 10000);
</script>

<?php }?>