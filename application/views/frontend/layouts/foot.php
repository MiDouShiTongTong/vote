<div class="common-footer">
    <input type="hidden" name="site_url" value="<?php echo site_url(); ?>">
    <input type="hidden" name="base_url" value="<?php echo base_url(); ?>">

    <!-- load -->
    <div id="load">
        <div class="preloader-wrapper active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>

            <div class="spinner-layer spinner-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>

            <div class="spinner-layer spinner-yellow">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>

            <div class="spinner-layer spinner-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="<?php echo base_url('public/common/libraries/materialize/js/materialize.min.js'); ?>"></script>

    <!-- Customer -->
    <script src="<?php echo base_url('public/frontend/js/common.js'); ?>"></script>
    <script src="<?php echo base_url('public/frontend/js/tool.js'); ?>"></script>

    <!-- pjax -->
    <script src="<?php echo base_url('public/common/plugins/jQuery-pjax/jquery.pjax.js'); ?>"></script>
    <!-- nprogress -->
    <script src="<?php echo base_url('public/common/plugins/nprogress/js/nprogress.js'); ?>"></script>

    <script>

        function a ()
        {
            var a = 1
            return function (b)
            {
                return a + b
            }
        }

        var b = a();
        b(1);

        (function () {
            var elem = {
                pjaxContainer: $('#pjax-container')
            };
            var event = {
                pageInit: function () {
                    // pjax setting
                    $.pjax.defaults.maxCacheLength = 0; // 全局禁止缓存
                    $.pjax.defaults.timeout = 10000;    // 请求超时 时间 毫秒
                    $.pjax.defaults.replate = true; // 全局禁止缓存

                    // pjax event
                    $(document).pjax('.pjax', '#pjax-container').on('pjax:click', function () {
                        // do something...
                    }).on('pjax:send', function (e) {
                        NProgress.start();
                        elem.pjaxContainer.removeClass('slide-to-top-opacity-show').addClass('slide-to-bottom-opacity-none');
                    }).on('pjax:complete', function () {
                        NProgress.done();
                        // 改变title
                        document.title = $('#page-title').text();
                        elem.pjaxContainer.removeClass('slide-to-bottom-opacity-none').addClass('slide-to-top-opacity-show');
                    });
                }
            };
            event.pageInit();
        })();

    </script>
</div>