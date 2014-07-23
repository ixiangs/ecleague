<?php
$this->beginBlock('content');
?>
    <div class="container">
        <div class="list-group" id="article_list">
            <?php foreach ($this->models as $model): ?>
                <a href="<?php echo $model->getLink(); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">[<?php echo $model->getCategoryName(); ?>
                        ] <?php echo $model->getTitle(); ?></h4>

                    <p class="list-group-item-text"><?php echo $model->getIntroduction(); ?></p>
                </a>
            <?php endforeach; ?>
            <?php foreach ($this->models as $model): ?>
                <a href="<?php echo $model->getLink(); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">[<?php echo $model->getCategoryName(); ?>
                        ] <?php echo $model->getTitle(); ?></h4>

                    <p class="list-group-item-text"><?php echo $model->getIntroduction(); ?></p>
                </a>
            <?php endforeach; ?>
            <?php foreach ($this->models as $model): ?>
                <a href="<?php echo $model->getLink(); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">[<?php echo $model->getCategoryName(); ?>
                        ] <?php echo $model->getTitle(); ?></h4>

                    <p class="list-group-item-text"><?php echo $model->getIntroduction(); ?></p>
                </a>
            <?php endforeach; ?>
            <?php foreach ($this->models as $model): ?>
                <a href="<?php echo $model->getLink(); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">[<?php echo $model->getCategoryName(); ?>
                        ] <?php echo $model->getTitle(); ?></h4>

                    <p class="list-group-item-text"><?php echo $model->getIntroduction(); ?></p>
                </a>
            <?php endforeach; ?>
            <?php foreach ($this->models as $model): ?>
                <a href="<?php echo $model->getLink(); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">[<?php echo $model->getCategoryName(); ?>
                        ] <?php echo $model->getTitle(); ?></h4>

                    <p class="list-group-item-text"><?php echo $model->getIntroduction(); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="javascript:vodi(0);" id="loading_text" class="center-block bg-primary text-center center" style="padding: 15px;margin-bottom: 10px;">
            <span class="glyphicon glyphicon-refresh" style="font-size:24px;"></span>
        </a>
    </div>
<?php $this->beginScript('content_list'); ?>
    <script language="JavaScript">
        var pageIndex = 1;
        var over = false;
        var loading = false;
        var artilceRequestUrl = '<?php echo $this->router->buildUrl('list'); ?>';
        var artilceRequest = new Request.JSON({url: artilceRequestUrl, 'onSuccess': function (data) {
            over = data.over;
            var el = $('article_list');
            for (var i = 0; i < data.data.length; i++) {
                var t = data.data[i];
                var h = '<a href="{link}" class="list-group-item">'
                    + '<h4 class="list-group-item-heading">[{category}] {title}</h4>'
                    + '<p class="list-group-item-text">{introduction}</p></a>';
                el.adopt(Elements.from(h.substitute(t)));
            }
            loading = false;
            if (over) {
                $('loading_text')
                    .addClass('bg-success').removeClass('bg-primary')
                    .set('href', '#')
                    .set('html', '<span class="glyphicon glyphicon-arrow-up" style="font-size:24px;"></span>');
            }
        }});
        window.addEvent('scroll', function () {
            var s = window.getScroll();
            var ss = window.getScrollSize();
            if ((ss.y - document.body.getHeight() - s.y) <= ss.y * 0.1) {
                if (loading || over) {
                    return;
                }
                pageIndex += 1;
                loading = true;
                artilceRequest.get({websiteid: '<?php echo $this->website->getId(); ?>',
                        categoryid: '<?php echo $this->request->getQuery('categoryid')?>',
                        pageindex: pageIndex});
            }
        });
    </script>
<?php
$this->endScript();
$this->endBlock();
echo $this->includeTemplate('layout/base');