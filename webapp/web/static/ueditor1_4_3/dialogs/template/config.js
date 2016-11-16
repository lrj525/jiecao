/**
 * Created with JetBrains PhpStorm.
 * User: xuheng
 * Date: 12-8-8
 * Time: 下午2:00
 * To change this template use File | Settings | File Templates.
 */


var templates = [
    {
        "pre": "titleshort.jpg",
        'title': lang.blank,
        'preHtml': '<p><span style="font-size: 16px;"><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; background-color: rgb(255, 255, 255);">查看</span><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; text-decoration: underline; background-color: rgb(255, 255, 255);"><a href="ShortRefunInfo.aspx" target="_self">短途/周边活动退款政策</a></span></span></p>',
        "html": '<p><span style="font-size: 16px;"><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; background-color: rgb(255, 255, 255);">查看</span><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; text-decoration: underline; background-color: rgb(255, 255, 255);"><a href="ShortRefunInfo.aspx" target="_self">短途/周边活动退款政策</a></span></span></p>'

    },
    {
        "pre": "titlelong.jpg",
        'title': lang.blank,
        'preHtml': '<p><span style="font-size: 16px;"><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; background-color: rgb(255, 255, 255);">查看</span><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; text-decoration: underline; background-color: rgb(255, 255, 255);"><a href="LongRefunInfo.aspx" target="_self">长线/长途活动退款政策</a></span></span></p>',
        "html": '<p><span style="font-size: 16px;"><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; background-color: rgb(255, 255, 255);">查看</span><span style="color: rgb(0, 0, 0); font-family: Verdana; line-height: normal; widows: auto; text-decoration: underline; background-color: rgb(255, 255, 255);"><a href="LongRefunInfo.aspx" target="_self">长线/长途活动退款政策</a></span></span></p>'

    },
    {
        "pre": "title1.png",
        'title': lang.blank,
        'preHtml': '<section class="s0002" style="display: inline-block; height: 2em; max-width: 100%; line-height: 1em;box-sizing: border-box; border-top: 1.1em solid #00BBEC; border-bottom: 1.1em solid #00BBEC; border-right: 1em solid transparent;"> <section style="height: 2em; margin-top: -1em; color: white; padding: 0.5em 1em; max-width: 100%; white-space: nowrap;text-overflow: ellipsis;">这里输入标题</section></section>',
        "html": '<section class="s0002" style="display: inline-block; height: 2em; max-width: 100%; line-height: 1em;box-sizing: border-box; border-top: 1.1em solid #00BBEC; border-bottom: 1.1em solid #00BBEC; border-right: 1em solid transparent;"> <section style="height: 2em; margin-top: -1em; color: white; padding: 0.5em 1em; max-width: 100%; white-space: nowrap;text-overflow: ellipsis;">这里输入标题</section></section>'

    },
    {
        "pre": "title2.png",
        'title': lang.blank,
        'preHtml': '<h2 style="margin: 8px 0px 0px; padding: 0px; height: 32px; text-align: justify; line-height: 18px; font-family: inherit; font-size: 16px; font-weight: normal; white-space: normal;"><span style="padding: 0px 2px 3px; line-height: 28px; float: left; display: block;"><span class="i_num" style="padding: 4px 10px; border-radius: 80% 100% 90% 20%; color: #ffffff; margin-right: 8px; background-color: #00BBEC;">这可输入标题</span></span></h2>',
        "html": '<h2 style="margin: 8px 0px 0px; padding: 0px; height: 32px; text-align: justify; line-height: 18px; font-family: inherit; font-size: 16px; font-weight: normal; white-space: normal;"><span style="padding: 0px 2px 3px; line-height: 28px; float: left; display: block;"><span class="i_num" style="padding: 4px 10px; border-radius: 80% 100% 90% 20%; color: #ffffff; margin-right: 8px; background-color: #00BBEC;">这可输入标题</span></span></h2>'

    },
    {
        "pre": "title3.png",
        'title': lang.blank,
        'preHtml': '<h2 style="margin: 8px 0px 0px; padding: 0px; height: 32px; text-align: justify; color: #00BBEC; line-height: 18px; font-family: inherit; font-size: 16px; font-weight: normal; white-space: normal;"><span style="padding: 0px 2px 3px; line-height: 28px; float: left; display: block;"><span class="i_num" style="padding: 4px 10px; border-radius: 80% 100% 90% 20%; color: rgb(255, 255, 255); margin-right: 8px; background-color: #00BBEC;">1</span><strong class="ue_t">这可输入标题</strong></span></h2>',
        "html": '<h2 style="margin: 8px 0px 0px; padding: 0px; height: 32px; text-align: justify; color: #00BBEC; line-height: 18px; font-family: inherit; font-size: 16px; font-weight: normal; white-space: normal;"><span style="padding: 0px 2px 3px; line-height: 28px; float: left; display: block;"><span class="i_num" style="padding: 4px 10px; border-radius: 80% 100% 90% 20%; color: rgb(255, 255, 255); margin-right: 8px; background-color: #00BBEC;">1</span><strong class="ue_t">这可输入标题</strong></span></h2>'

    },
    {
        "pre": "title4.png",
        'title': lang.blank,
        'preHtml': '<h2 style="margin: 8px 0px 0px; padding: 0px; height: 32px; text-align: justify; color: rgb(62, 62, 62); line-height: 18px; font-family: inherit; font-size: 16px; font-weight: normal; border-bottom-color: rgb(227, 227, 227); border-bottom-width: 1px; border-bottom-style: solid; white-space: normal;"><span style="padding: 0px 2px 3px; color: rgb(0, 112, 192); line-height: 28px; border-bottom-color: #00BBEC; border-bottom-width: 2px; border-bottom-style: solid; float: left; display: block;"><span class="i_num" style="padding: 4px 10px; border-radius: 80% 100% 90% 20%; color: rgb(255, 255, 255); margin-right: 8px; background-color: #00BBEC;">序号.</span><strong class="ue_t i_tit" style="color: #00BBEC;">标题党</strong></span></h2>',
        "html": '<h2 style="margin: 8px 0px 0px; padding: 0px; height: 32px; text-align: justify; color: rgb(62, 62, 62); line-height: 18px; font-family: inherit; font-size: 16px; font-weight: normal; border-bottom-color: rgb(227, 227, 227); border-bottom-width: 1px; border-bottom-style: solid; white-space: normal;"><span style="padding: 0px 2px 3px; color: rgb(0, 112, 192); line-height: 28px; border-bottom-color: #00BBEC; border-bottom-width: 2px; border-bottom-style: solid; float: left; display: block;"><span class="i_num" style="padding: 4px 10px; border-radius: 80% 100% 90% 20%; color: rgb(255, 255, 255); margin-right: 8px; background-color: #00BBEC;">序号.</span><strong class="ue_t i_tit" style="color: #00BBEC;">标题党</strong></span></h2>'
    },
    {
        "pre": "title5.png",
        'title': lang.blank,
        'preHtml': '<blockquote class="f" style="max-width: 100%; margin: 0; padding: 5px 15px; color: #ffffff; line-height: 25px; font-weight: bold; background-color: #00BBEC; text-align: left;border-radius: 5px 5px 0 0;border: 0;"><span class="ue_t">这输入标题</span></blockquote><blockquote class="l" style="max-width: 100%; margin: 0px; padding: 10px 15px 20px 15px; border-radius: 0 0 5px 5px;border: 1px solid #00BBEC; line-height: 25px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>',
        'html': '<blockquote class="f" style="max-width: 100%; margin: 0; padding: 5px 15px; color: #ffffff; line-height: 25px; font-weight: bold; background-color: #00BBEC; text-align: left;border-radius: 5px 5px 0 0;border: 0;"><span class="ue_t">这输入标题</span></blockquote><blockquote class="l" style="max-width: 100%; margin: 0px; padding: 10px 15px 20px 15px; border-radius: 0 0 5px 5px;border: 1px solid #00BBEC; line-height: 25px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>'
    },
    {
        "pre": "title6.png",
        'title': lang.blank,
        'preHtml': '<fieldset style="margin: 0px; padding: 5px; border: 1px solid #00BBEC;"><legend style="margin: 0px 10px;width:auto"><span style="padding: 5px 10px; color: #ffffff; font-weight: bold; font-size: 14px; background-color: #00BBEC;border-radius: 5px;" class="ue_t">这输入标题</span></legend><blockquote style="margin: 0px; padding: 10px; border: 0;"><p class="ue_t">我的标题是圆角，如果看我和上面长得一样，那是因为你的浏览器版本被全国 N% 的电脑击败了。</p></blockquote></fieldset>',
        'html': '<fieldset style="margin: 0px; padding: 5px; border: 1px solid #00BBEC;"><legend style="margin: 0px 10px;width:auto"><span style="padding: 5px 10px; color: #ffffff; font-weight: bold; font-size: 14px; background-color: #00BBEC;border-radius: 5px;" class="ue_t">这输入标题</span></legend><blockquote style="margin: 0px; padding: 10px; border: 0;"><p class="ue_t">我的标题是圆角，如果看我和上面长得一样，那是因为你的浏览器版本被全国 N% 的电脑击败了。</p></blockquote></fieldset>'
    },
    {
        "pre": "title7.png",
        'title': lang.blank,
        'preHtml': '<blockquote style="margin: 0px; padding: 15px; border: 1px solid #00BBEC;border-radius: 5px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容</p></blockquote>',
        'html': '<blockquote style="margin: 0px; padding: 15px; border: 1px solid #00BBEC;border-radius: 5px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容</p></blockquote>'
    },
    {
        "pre": "title8.png",
        'title': lang.blank,
        'preHtml': '<blockquote style="margin: 0px; padding: 15px; border: 3px dashed #00BBEC;border-radius: 10px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>',
        'html': '<blockquote style="margin: 0px; padding: 15px; border: 3px dashed #00BBEC;border-radius: 10px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>'
    },
    {
        "pre": "title9.png",
        'title': lang.blank,
        'preHtml': '<blockquote style="margin:0 ;border-bottom: rgb(225,225,225) 2px dotted; text-align: justify; border-left: rgb(225,225,225) 2px dotted; padding-bottom: 10px; widows: 2; text-transform: none; background-color: #00BBEC; text-indent: 0px; padding-left: 20px; padding-right: 20px; font: medium/21px 微软雅黑; max-width: 100%; white-space: normal; orphans: 2; letter-spacing: normal; color: #ffffff; border-top: rgb(225,225,225) 2px dotted; border-right: rgb(225,225,225) 2px dotted; word-spacing: 0px; padding-top: 10px; box-shadow: rgb(225, 225, 225) 5px 5px 2px; border-top-left-radius: 0.5em 4em; border-top-right-radius: 3em 0.5em; -webkit-text-size-adjust: none; -webkit-text-stroke-width: 0px; border-bottom-right-radius: 0.5em 1em; border-bottom-left-radius: 0em 3em"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>',
        'html': '<blockquote style="margin:0 ;border-bottom: rgb(225,225,225) 2px dotted; text-align: justify; border-left: rgb(225,225,225) 2px dotted; padding-bottom: 10px; widows: 2; text-transform: none; background-color: #00BBEC; text-indent: 0px; padding-left: 20px; padding-right: 20px; font: medium/21px 微软雅黑; max-width: 100%; white-space: normal; orphans: 2; letter-spacing: normal; color: #ffffff; border-top: rgb(225,225,225) 2px dotted; border-right: rgb(225,225,225) 2px dotted; word-spacing: 0px; padding-top: 10px; box-shadow: rgb(225, 225, 225) 5px 5px 2px; border-top-left-radius: 0.5em 4em; border-top-right-radius: 3em 0.5em; -webkit-text-size-adjust: none; -webkit-text-stroke-width: 0px; border-bottom-right-radius: 0.5em 1em; border-bottom-left-radius: 0em 3em"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>'
    },
    {
        "pre": "title10.png",
        'title': lang.blank,
        'preHtml': '<blockquote class="f" style="max-width: 100%; margin: 0; padding: 5px 15px; color: rgb(255, 255, 255); line-height: 25px; font-weight: bold; background-color: #00BBEC; text-align: left;border-radius: 5px 5px 0 0;border: 0;"><span class="ue_t">这输入标题</span></blockquote><blockquote class="l" style="max-width: 100%; margin: 0px; padding: 10px 15px 20px 15px; border-radius: 0 0 5px 5px;border: 0 ;background-color: #efefef; line-height: 25px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>',
        'html': '<blockquote class="f" style="max-width: 100%; margin: 0; padding: 5px 15px; color: rgb(255, 255, 255); line-height: 25px; font-weight: bold; background-color: #00BBEC; text-align: left;border-radius: 5px 5px 0 0;border: 0;"><span class="ue_t">这输入标题</span></blockquote><blockquote class="l" style="max-width: 100%; margin: 0px; padding: 10px 15px 20px 15px; border-radius: 0 0 5px 5px;border: 0 ;background-color: #efefef; line-height: 25px;"><p class="ue_t">毅行少儿成长营地说可在这输入内容。</p></blockquote>'
    },
    {
        "pre": "title11.png",
        'title': lang.blank,
        'preHtml': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/mnoDuttLdiaGOkNHmGLdVKbCDbyJMN8fU7JxQ7rpsKnPIzLGhIk59UnDLrIic9JjicxXTY1Hib2ibNWlu7YDkOvAcRQ/0?wx_fmt=jpeg"/></p>',
        'html': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/mnoDuttLdiaGOkNHmGLdVKbCDbyJMN8fU7JxQ7rpsKnPIzLGhIk59UnDLrIic9JjicxXTY1Hib2ibNWlu7YDkOvAcRQ/0?wx_fmt=jpeg"/></p>'
    },
    {
        "pre": "title12.png",
        'title': lang.blank,
        'preHtml': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/mXxlqaPRlDzTXa45W9TJhxYHa6M3RZZCLxVUAdSTHz3JTaL4iaskuZD5Od3DO42S8BSgcJMdPVvX0qeIjkuoPqQ/0?wx_fmt=jpeg"/></p>',
        'html': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/mXxlqaPRlDzTXa45W9TJhxYHa6M3RZZCLxVUAdSTHz3JTaL4iaskuZD5Od3DO42S8BSgcJMdPVvX0qeIjkuoPqQ/0?wx_fmt=jpeg"/></p>'
    },
    {
        "pre": "title13.png",
        'title': lang.blank,
        'preHtml': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/ppIRjPJeO3icqFxQ5d4cGfjUYchhwWL7pcRoYvDgdXbG7BHVJ0ibNE090pjtz7dMAsqliak5TRsy0b8Lm0KZWFyDw/0?wx_fmt=jpeg"/></p>',
        'html': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/ppIRjPJeO3icqFxQ5d4cGfjUYchhwWL7pcRoYvDgdXbG7BHVJ0ibNE090pjtz7dMAsqliak5TRsy0b8Lm0KZWFyDw/0?wx_fmt=jpeg"/></p>'
    },
    {
        "pre": "title14.png",
        'title': lang.blank,
        'preHtml': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/mnoDuttLdiaGOkNHmGLdVKbCDbyJMN8fUgwHHFyk0yM57NR43qdYwyWN1gG1ibGWGvBYIbg4n745uELBicpRicljAA/0?wx_fmt=jpeg"/></p>',
        'html': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/mnoDuttLdiaGOkNHmGLdVKbCDbyJMN8fUgwHHFyk0yM57NR43qdYwyWN1gG1ibGWGvBYIbg4n745uELBicpRicljAA/0?wx_fmt=jpeg"/></p>'
    },
    {
        "pre": "title15.png",
        'title': lang.blank,
        'preHtml': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/UYumC8SBmaa6QZE1nSJe2Kf6o5TBe46oBcDDYLsH3xXOiao7pfxZJoOBeVicEbl46EicwiaDnzeSrPXfNo7ePV9MWg/0?wx_fmt=jpeg"/></p>',
        'html': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=http://mmbiz.qpic.cn/mmbiz/UYumC8SBmaa6QZE1nSJe2Kf6o5TBe46oBcDDYLsH3xXOiao7pfxZJoOBeVicEbl46EicwiaDnzeSrPXfNo7ePV9MWg/0?wx_fmt=jpeg"/></p>'
    },
    {
        "pre": "title16.png",
        'title': lang.blank,
        'preHtml': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=https://mmbiz.qlogo.cn/mmbiz/6xsuhALdNEr0ibLDATPbiaQoI0OyJzZP81jDYTckoPrfeX088lekl55f4B43DyAGgGfvtCXmEmg8KtLjtQ7yMLiaw/0?wx_fmt=jpeg"/></p>',
        'html': '<p><img style="height: auto !important; width:100%;" src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=https://mmbiz.qlogo.cn/mmbiz/6xsuhALdNEr0ibLDATPbiaQoI0OyJzZP81jDYTckoPrfeX088lekl55f4B43DyAGgGfvtCXmEmg8KtLjtQ7yMLiaw/0?wx_fmt=jpeg"/></p>'
    }


];