<?php

namespace App\Admin\Controllers;

use App\House;
use App\Http\Controllers\Controller;
use App\IntentCustomer;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Row;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class HouseController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('房源列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('房源详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑房源')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('新增房源')
            ->body($this->form());
    }

    public function generatorText($id, Content $content)
    {
        return $content
            ->header('房源信息')
            ->row(function (Row $row) use ($id) {
                $row->column(12, function (Column $column) use ($id) {
                    $model = House::find($id);
                    $text= '';
                    if (!empty($model->house_type)) {
                        $text .= House::$TYPE[$model->house_type];
                    }
                    if (!empty($model->village)) {
                        $text .= $model->village . '， ';
                    }
                    if (!empty($model->floor)) {
                        $text .= $model->floor . '楼， ';
                    }
                    if (!empty($model->house_area)) {
                        $text .= $model->house_area . '平方， ';
                    }
                    if (!empty($model->road)) {
                        $text .= $model->road . '， ';
                    }
                    if (!empty($model->price)) {
                        $text .= $model->price . '元， ';
                    }
                    if (!empty($model->decorate)) {
                        $text .= House::$DECORATE[$model->decorate] . '， ';
                    }
                    if (!empty($model->house_size)) {
                        $text .= House::$HOUSE_SIZE[$model->house_size] . '， ';
                    }
                    if (!empty($model->house_direction)) {
                        $text .= '房屋' . House::$HOUSE_DIRECTION[$model->house_direction] . '， ';
                    }
                    $text .= "有空调冰箱洗衣机电视机等家具，独立阳台可烧饭，随时可以看房。   联系方式：13735272852、13957578781 微信同号";
                    $column->row($text);
                });
            });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new House);
        $grid->disableExport();
        $grid->paginate(10);
        $grid->disableRowSelector();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $id = $actions->getKey();
            $actions->append("<a href=/admin/house/generator/$id><i class='fa fa-paper-plane'></i></a>");
            $actions->append("  <a href=/admin/transfer/$id/intent><i class='fa fa-male'></i></a>");
        });

        $grid->filter(function($filter){
            $filter->disableIdFilter();

            $filter->column(6, function ($filter) {
                $filter->like('village', '房屋小区');
                $filter->like('floor', '房屋楼层');
                $filter->between('register_time', '登记时间')->datetime();
                $filter->where(function ($query) {
                    $price = $this->input * 100;
                    $query->whereRaw("`price` >= {$price}");
                }, '价格 >=');
                $filter->where(function ($query) {
                    $price = $this->input * 100;
                    $query->whereRaw("`price` <= {$price}");
                }, '价格 <=');
                $filter->in('decorate', '装修程度')->select(House::$DECORATE);
            });

            $filter->column(6, function ($filter) {
                $filter->like('area', '房屋地区');
                $filter->in('house_status', '房源状态')->multipleSelect(House::$STATUS);
                $filter->in('house_type', '房屋类型')->multipleSelect(House::$TYPE);
                $filter->in('house_direction', '房屋朝向')->multipleSelect(House::$HOUSE_DIRECTION);
                $filter->in('house_size', '房屋规模')->multipleSelect(House::$HOUSE_SIZE);
            });
        });

        $grid->area('地区')->sortable();
        $grid->village('小区')->sortable();
        $grid->number('幢/门牌号')->sortable();
        $grid->floor('楼层')->sortable();
        $grid->road('街道')->sortable();
        $grid->price('价格')->sortable();
        $grid->phone('联系方式')->sortable();
        $grid->house_status('状态')->display(function (){
            if (!empty($this->house_status)) {
                return House::$STATUS[$this->house_status];
            }
            return '';
        });

        $grid->house_size('规模')->display(function (){
            if (!empty($this->house_size)) {
                return House::$HOUSE_SIZE[$this->house_size];
            }
            return '';
        });

        $grid->house_area('面积')->display(function (){
            if (!empty($this->house_area)) {
                return $this->house_area . '平方';
            }
            return '';
        })->sortable();

        $grid->house_direction('朝向')->display(function (){
            if (!empty($this->house_direction)) {
                return House::$HOUSE_DIRECTION[$this->house_direction];
            }
            return '';
        });

        $grid->decorate('装修')->display(function (){
            if (!empty($this->decorate)) {
                return House::$DECORATE[$this->decorate];
            }
            return '';
        });
        $grid->register_time('录入时间');
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(House::findOrFail($id));
        $show->city('城市');
        $show->area('地区');
        $show->village('小区名');
        $show->number('幢/门牌号');
        $show->floor('楼层');
        $show->road('街道');
        $show->price('价格');
        $show->phone('联系方式');
        $show->house_type('房屋类型')->as(function ($house_type){
            if (!empty($house_type)) {
                return House::$TYPE[$house_type];
            }
            return '';
        });
        $show->house_status('房屋状态')->as(function ($house_status){
            if (!empty($house_status)) {
                return House::$STATUS[$house_status];
            }
            return '';
        });
        $show->house_size('房屋规模')->as(function ($house_size){
            if (!empty($house_size)) {
                return House::$HOUSE_SIZE[$house_size];
            }
            return '';
        });
        $show->house_area('房屋面积')->as(function ($house_area) {
            if (!empty($house_area)) {
                return $house_area . '平方';
            }
            return '';
        });
        $show->is_cert('是否有房产证')->as(function ($is_cert){
            if ($is_cert === 1) {
                return '是';
            } else {
                return '否';
            }
        });
        $show->house_direction('房屋朝向')->as(function ($house_direction){
            if (!empty($house_direction)) {
                return House::$HOUSE_DIRECTION[$house_direction];
            }
            return '';
        });
        $show->decorate('装修程度')->as(function ($decorate) {
            if (!empty($decorate)) {
                return House::$DECORATE[$decorate];
            }
            return '';
        });
        $show->register_time('登记日期');
        $show->throw_time('出租/出售日期');
        $show->remark('备注信息');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new House);

        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableViewCheck();

        $form->text('city', '城市')->default('绍兴');
        $form->text('area', '地区');
        $form->text('village', '小区名');
        $form->text('number', '幢/门牌号');
        $form->text('floor', '楼层');
        $form->text('road', '街道');
        $form->text('price', '价格');
        $form->text('phone', '联系方式');
        $form->select('house_type', '房屋类型')->options(House::$TYPE);
        $form->select('house_status', '房屋状态')->options(House::$STATUS);
        $form->select('house_size', '房屋规模')->options(House::$HOUSE_SIZE);
        $form->select('house_direction', '房屋朝向')->options(House::$HOUSE_DIRECTION);
        $form->text('house_area', '房屋面积');
        $form->select('decorate', '装修程度')->options(House::$DECORATE);
        $form->select('is_cert', '是否有房产证')->options(['1' => '是', '2' => '否']);
        $form->datetime('register_time', '登记日期');
        $form->datetime('throw_time', '出租/出售日期');
        $form->textarea('remark', '备注信息');

        return $form;
    }
}
