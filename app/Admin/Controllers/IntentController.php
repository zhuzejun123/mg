<?php

namespace App\Admin\Controllers;

use App\IntentCustomer;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class IntentController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @param int $house_id
     * @return Content
     */
    public function index(Content $content, $house_id = null)
    {
        return $content
            ->header('客户列表')
            ->body($this->grid($house_id));
    }

    /**
     * @param $house_id
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($house_id, $id)
    {
        return $this->form()->update($id);
    }

    /**
     * public
     * @param null $house_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy($house_id = null, $id)
    {
        if ($this->form()->destroy($id)) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param mixed $house_id
     * @param Content $content
     * @return Content
     */
    public function show($house_id = null, $id, Content $content)
    {
        return $content
            ->header('客户详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param mixed $house_id
     * @param Content $content
     * @return Content
     */
    public function edit($house_id = null, $id, Content $content)
    {
        return $content
            ->header('编辑客户')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @param int $house_id
     * @return Content
     */
    public function create(Content $content, $house_id = null)
    {
            return $content
                ->header('创建客户')
                ->body($this->form($house_id));
    }

    /**
     * Make a grid builder.
     * @param int $house_id
     * @return Grid
     */
    protected function grid($house_id = null)
    {
        $grid = new Grid(new IntentCustomer);
        $grid->disableExport();
        $grid->paginate(10);
        $grid->disableRowSelector();

        if (!empty($house_id)) {
            $grid->model()->where('house_id', '=', $house_id);
        }

        $grid->filter(function($filter){
            $filter->disableIdFilter();

            $filter->column(6, function ($filter) {
                $filter->like('customer_phone', '客户手机号');
                $filter->like('customer_name', '客户名称');
                $filter->equal('price', '客户意向度')->select(IntentCustomer::$INTENT);
            });

            $filter->column(6, function ($filter) {
                $filter->between('watch_time', '看房日期')->datetime();
                $filter->between('sign_time', '签合同日期')->datetime();
            });
        });

        $grid->customer_name('客户名称');
        $grid->customer_phone('客户手机号');
        $grid->intent_price('客户报价')->sortable();
        $grid->final_price('最终价格')->sortable();
        $grid->intent('客户意向度')->sortable();
        $grid->watch_time('看房日期')->sortable();
        $grid->sign_time('签合同日期')->sortable();

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
        $show = new Show(IntentCustomer::findOrFail($id));
        $show->customer_name('客户名称');
        $show->customer_phone('客户手机号');
        $show->intent_price('客户报价');
        $show->final_price('最终价格');
        $show->intent('客户意向度 1-10');
        $show->watch_time('看房日期');
        $show->sign_time('签合同日期');


        return $show;
    }

    /**
     * Make a form builder.
     * @param int $house_id
     * @return Form
     */
    protected function form($house_id = null)
    {
        $form = new Form(new IntentCustomer);

        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableViewCheck();

        $form->text('customer_name', '客户名称');
        $form->text('customer_phone', '客户手机号');
        $form->text('intent_price', '客户报价');
        $form->text('final_price', '最终价格');
        $form->select('intent', '客户意向度 1-10')->options(IntentCustomer::$INTENT);
        $form->datetime('watch_time', '看房日期');
        $form->datetime('sign_time', '签合同日期');
        $form->hidden('house_id', 'house_id')->value($house_id);

        return $form;
    }
}
