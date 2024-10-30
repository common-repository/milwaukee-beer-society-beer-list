alert(blah);
jQuery(document).ready(function(){
    
    var isSmartphone = ( navigator.userAgent.match(/(iPad|iPhone|iPod|Android)/gi) ? true : false );

    var urlprefix = isSmartphone ? "untappd:///?beer=" : "https://untappd.com/beer/";
    jQuery('a[href*="12248854"]')               ///change to "untappd"
        .click(function(){
            var link = jQuery(this).attr('href');
            link = link.replace(/\/$/, '');
            var splitlink = link.split('/');
            var beernumber = splitlink[splitlink.length - 1];
            beernumber = '536535';              ///delete line
            if(isSmartphone){
                //var now = new Date().valueOf();                               //bring this block back
                //setTimeout(function () {
                //    if (new Date().valueOf() - now > 100) return;	
                //    window.location = "https://untappd.com/beer/" + beernumber;
                //}, 25);
                //window.location = "untappd:///?beer=" + beernumber;
            }else{
                //window.location = "https://untappd.com/beer/" + beernumber;       //restore this line
                window.location = "http://www.asp.net/web-api/overview/advanced/dependency-injection/" + beernumber; //delete line
            };
            return false;
        })
        .each(function (idx, elem) {
            var link = jQuery(elem).attr('href');
            link = link.replace(/\/$/, '');
            var splitlink = link.split('/');
            var beernumber = splitlink[splitlink.length - 1];
            beernumber = '536535';              ///delete line

            var origPara = jQuery(elem).parent('p');
            var newElem = jQuery('<div style="float:left"></div>');

            var div1 = jQuery('<div style="display:inline-block;width:60px;height:60px"></div>');
            //var img = jQuery('<img style="vertical-align:bottom; width:100%;height:100%;border:none"/>');
            //div1.append(img);
            newElem.append(div1);

            var div2 = jQuery('<div style="display:inline-block;"></div>');
            var div2_1 = jQuery('<div style="display:block;"></div>');
            div2_1.append(origPara.html());
            div2.append(div2_1);
            newElem.append(div2);

            //origPara.html(newElem);

            $.ajax({
                url: 'fail', // 'https://api.untappd.com/v4/beer/info/' + beernumber + '?client_id=0B728429FB98C64143CD4D267ED17450FADD2C7B&client_secret=4B008F08FB043AF63DED0343F681836A197E4499&compact=true',
                type: 'GET',
                async: true,
                contentType: "application/json",
                success: function (data) {
                    div1.append('<img style="vertical-align:top; width:100%;height:100%" src="https:\/\/d1c8v1qci5en44.cloudfront.net\/site\/beer_logos\/beer-536535_54e29_sm.jpeg" />')
                },
                complete: function (data) {
                    div1.append('<img style="width:100%;height:100%;vertical-align:middle"  src="https:\/\/d1c8v1qci5en44.cloudfront.net\/site\/beer_logos\/beer-536535_54e29_sm.jpeg" />')
                }
            });

        });
});