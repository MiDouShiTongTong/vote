<div class="search-bar-container mb-3">
    <div class="container-fluid">
        <div class="row">
            <div class="d-flex">

                <?php
                foreach ($conditions_info as $_key => $condition_info) {
                ?>
                <div class="mr-3">
                    <div class="form-group mb-0">
                        <label for="<?php echo $condition_info['field'] ?>" class="sr-only"></label>
                        <?php
                        switch ($condition_info['type']) {
                            case 'select':
                        ?>
                        <!-- select -->
                        <select name="condition-field" id="<?php echo $condition_info['field'] ?>" class="form-control condition-field" data-field="<?php echo $condition_info['field'] ?>">
                            <option value=""><?php echo $condition_info['field_placeholder']; ?></option>
                            <?php
                            // 是否有默认条件
                            $search_default_value = isset($condition_info['search_default_value']) ? $condition_info['search_default_value'] : '';
                            foreach ($condition_info['option'] as  $_key_select_option => $select_option) {
                                $selected = '';
                                if (!empty($search_default_value)) {
                                    // 默认条件
                                    if ($select_option->id == $search_default_value) {
                                        $selected = 'selected';
                                    }
                                }
                            ?>
                            <option value="<?php echo $select_option->id; ?>" data-field-placeholder="<?php echo $condition_info['field_placeholder'] ?>" <?php echo $selected; ?>>
                                <?php echo $select_option->value; ?>
                            </option>
                            <?php
                                }
                            ?>
                        </select>
                            <?php
                                break;
                            case 'text':
                            ?>
                        <!-- text -->
                        <input type="text" name="condition-value" class="form-control condition-field" id="<?php echo $condition_info['field'] ?>" placeholder="<?php echo $condition_info['field_placeholder']; ?>" data-field="<?php echo $condition_info['field'] ?>">
                            <?php
                                break;
                            ?>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="col-xs-3 p-0">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary search-do"><?php echo $this->lang->line('search'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- search js -->
    <script>
        $(function () {
            var elem = {
                searchDo: $('.search-do')
            };

            var event = {
                // 初始化事件
                eventInit: function () {
                    elem.searchDo.off('click').on('click', event.searchDo);
                },
                // 执行搜索
                searchDo: function () {
                    // 模态框搜索列表页不搜索
                    if (window.dataTableSearchModalFlag == 1) {
                        $('.dtb-modal').DataTable().ajax.reload();
                        return;
                    }
                    // 关闭窗口 显示列表
                    cEvent.closeDataActionContainer();
                    // 重新加载数据
                    $('.dtb').DataTable().ajax.reload();
                }
            };

            event.eventInit();
        });

    </script>
</div>