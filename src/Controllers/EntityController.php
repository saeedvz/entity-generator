<?php

namespace SaeedVaziry\EntityGenerator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class EntityController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('entity-generator::index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $rules = [
            'entity_name'           => 'required',
            'table_name'            => 'required',
            'model_name'            => 'required',
            'controller_name'       => 'required',
            'route_prefix'          => 'required',
            'views_dir'             => 'required',
            'table_field_name.*'    => 'required',
            'table_field_display.*' => 'required',
            'table_field_type.*'    => 'required|in:string,integer,longText',
        ];
        $this->validate($request, $rules);

        if (Schema::hasTable($request->table_name)) {
            return redirect()->back()->with([
                'alert'   => 'danger',
                'message' => $request->table_name . ' table already exists!'
            ])->withInput($request->input());
        }
        if (class_exists('App\\' . $request->model_name)) {
            return redirect()->back()->with([
                'alert'   => 'danger',
                'message' => $request->model_name . ' model already exists!'
            ])->withInput($request->input());
        }
        if (class_exists('App\\Http\\Controllers\\' . $request->controller_name)) {
            return redirect()->back()->with([
                'alert'   => 'danger',
                'message' => $request->controller_name . ' controller already exists!'
            ])->withInput($request->input());
        }
        if (File::exists(resource_path('views/' . $request->views_dir))) {
            return redirect()->back()->with([
                'alert'   => 'danger',
                'message' => $request->views_dir . ' view directory already exists!'
            ])->withInput($request->input());
        }

        $this->createMigration($request);
        $this->createModel($request);
        $this->createController($request);
        $this->createRoutes($request);
        $this->createViews($request);

        return redirect()->back()->with([
            'alert'   => 'success',
            'message' => 'Entity created successfully'
        ]);
    }

    /**
     * @param Request $request
     */
    private function createMigration(Request $request)
    {
        $tableFields = "";
        foreach ($request->table_field_name as $key => $value) {
            $tableFields .= '$table->' . $request->table_field_type[$key] . '(\'' . $request->table_field_name[$key] . '\');' . "\n";
        }
        $migrationSample = File::get(__DIR__ . '/../SampleEntity/Migration.sample');
        $migration = str_replace('{u_table_name}', ucfirst($request->table_name), $migrationSample);
        $migration = str_replace('{table_name}', $request->table_name, $migration);
        $migration = str_replace('{table_fields}', $tableFields, $migration);
        File::put(base_path('database/migrations/' . date('Y_m_d') . '_000000_create_' . $request->table_name . '_table.php'), $migration);
        Artisan::call('migrate');
    }

    /**
     * @param Request $request
     */
    private function createModel(Request $request)
    {
        $fillables = "[";
        foreach ($request->table_field_name as $key => $value) {
            $fillables .= '\'' . $request->table_field_name[$key] . '\',';
        }
        $fillables .= '\'status\',';
        $fillables .= "]";

        $tableFields = "[";
        foreach ($request->table_field_name as $key => $value) {
            $tableFields .= '\'' . $request->table_field_name[$key] . '\',';
        }
        $tableFields .= '\'created_at\',';
        $tableFields .= "]";

        $formFields = "[";
        foreach ($request->table_field_name as $key => $value) {
            $formFields .= '\'' . $request->table_field_name[$key] . '\' => \'required\',';
        }
        $formFields .= "]";

        $modelSample = File::get(__DIR__ . '/../SampleEntity/Model.sample');
        $model = str_replace('{model_name}', $request->model_name, $modelSample);
        $model = str_replace('{table_name}', "'" . $request->table_name . "'", $model);
        $model = str_replace('{fillables}', $fillables, $model);
        $model = str_replace('{table_fields}', $tableFields, $model);
        $model = str_replace('{view_fields}', $tableFields, $model);
        $model = str_replace('{form_fields}', $formFields, $model);
        File::put(app_path($request->model_name . '.php'), $model);
    }

    /**
     * @param Request $request
     */
    private function createController(Request $request)
    {
        $controllerSample = File::get(__DIR__ . '/../SampleEntity/Controller.sample');
        $controller = str_replace('{controller_name}', $request->controller_name, $controllerSample);
        $controller = str_replace('{model_name}', $request->model_name, $controller);
        $controller = str_replace('{views_dir}', $request->views_dir, $controller);
        $controller = str_replace('{route_prefix}', $request->route_prefix, $controller);
        $controller = str_replace('{table_name}', $request->table_name, $controller);
        File::put(app_path('Http/Controllers/' . $request->controller_name . '.php'), $controller);
    }

    /**
     * @param Request $request
     */
    private function createRoutes(Request $request)
    {
        $routesSample = File::get(__DIR__ . '/../SampleEntity/Routes.sample');
        $routes = str_replace('{route_prefix}', $request->route_prefix, $routesSample);
        $routes = str_replace('{controller_name}', $request->controller_name, $routes);

        $router = File::get(base_path('routes/web.php'));
        File::put(base_path('routes/web.php'), $router . "\n\n" . $routes);
    }

    /**
     * @param Request $request
     */
    private function createViews(Request $request)
    {
        if (!File::exists(resource_path('views/' . $request->views_dir))) {
            File::makeDirectory(resource_path('views/' . $request->views_dir));
        }
        $indexSample = File::get(__DIR__ . '/../SampleEntity/Views/index.sample');
        $index = str_replace('{route_prefix}', $request->route_prefix, $indexSample);
        $index = str_replace('{model_name}', $request->model_name, $index);
        File::put(resource_path('views/' . $request->views_dir . '/' . 'index.blade.php'), $index);

        $createSample = File::get(__DIR__ . '/../SampleEntity/Views/create.sample');
        $create = str_replace('{route_prefix}', $request->route_prefix, $createSample);
        $create = str_replace('{model_name}', $request->model_name, $create);
        File::put(resource_path('views/' . $request->views_dir . '/' . 'create.blade.php'), $create);

        $viewSample = File::get(__DIR__ . '/../SampleEntity/Views/view.sample');
        $view = str_replace('{route_prefix}', $request->route_prefix, $viewSample);
        $view = str_replace('{model_name}', $request->model_name, $view);
        File::put(resource_path('views/' . $request->views_dir . '/' . 'view.blade.php'), $view);

        $editSample = File::get(__DIR__ . '/../SampleEntity/Views/edit.sample');
        $edit = str_replace('{route_prefix}', $request->route_prefix, $editSample);
        $edit = str_replace('{model_name}', $request->model_name, $edit);
        File::put(resource_path('views/' . $request->views_dir . '/' . 'edit.blade.php'), $edit);
    }
}
