<?php

namespace App\Http\Livewire\Crud;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * 
 */
class Main extends Component
{
    public static string $modelTypeString = 'model:';
    public static string $modelCrudPropertyName = 'crud';

    public Collection $items;
    public array $data = [];
    public array $fields = []; // see mount()
    public $editing = null;
    
    public bool $show_create_panel = true;
    public bool $show_list = true;

    public string $model = ''; // E.g. '\App\Model\Order'

    protected function modelCrudInfo() :array
    {
        if (empty($this->model)) {
            return [];
        }

        $crudPropertyName = self::$modelCrudPropertyName;
        $modelCrudInfo = $this->model::$$crudPropertyName;

        if (!is_array($modelCrudInfo)) {
            return [];
        }

        return $modelCrudInfo;
    }

    protected function fields()
    {
        $modelCrudInfo = $this->modelCrudInfo();

        if(isset($modelCrudInfo['fields'])) {
            return $modelCrudInfo['fields'];
        }

        return [];
    }

    public function mount($model = '')
    {
        if (!empty($model)) {
            $this->model = $model;
        }
        $this->fields = $this->createPublicFieldsProperty();
    }

    protected function rules()
    {
        $rules = [];

        foreach ($this->fields() as $key => $info) {
            if (isset($info['validation'])) {
                $key = 'data.' . $key;
                $rules[$key] = $info['validation'];
            }
        }

        return $rules;
    }

    protected function makeFieldKey($property) :string
    {
        return 'data.' . $property;
    }

    protected function isModelType($text)
    {
        return stripos($text, self::$modelTypeString) !== false;
    }

    protected function extracModelFromModelType($text)
    {
        $pieces = explode('|', $text);

        if (count($pieces) >= 2) {
            $text = $pieces[0];
        }

        return str_replace(self::$modelTypeString, '', $text);
    }    

    protected function extracValueFieldFromModelType($text)
    {
        $pieces = explode('|', $text);

        if (count($pieces) <= 1) {
            return 'name'; // TODO: make dybamic
        }

        return $pieces[1];
    }    

    protected function fillOptionsForModelType($type)
    {
        $model = $this->extracModelFromModelType($type);
        $idField = 'id'; // TODO: make dynamic?
        $valueField = $this->extracValueFieldFromModelType($type);
       
        $options = $model::all()->map(function($item) use ($idField, $valueField) {
            $key = $item[$idField];
            $value = $item[$valueField];
            return [
                'id' => $key,
                'text' => $value
            ];
        });

        return $options;
    }

    protected function fillOptionsForSelectType(array $info)
    {
        if (!isset($info['options'])) {
            return [];
        }

        $options = [];

        foreach($info['options'] as $key => $value) {
            $options[] = [
                'id' => $key,
                'text' => $value
            ];
        }

        return $options;
    }

    protected function createPublicFieldsProperty()
    {
        if (count($this->fields()) == 0) {
            return [];
        }

        $fields = [];

        foreach($this->fields() as $expectedKey => $info) {
            $key = $this->makeFieldKey($expectedKey);
            $fields[$key] = $info;
            $fields[$key]['property'] = $expectedKey;

            if (!isset($info['type'])) {
                continue;
            }

            if ($this->isModelType($info['type'])) {
                $fields[$key]['type'] = 'select';
                $fields[$key]['options'] = $this->fillOptionsForModelType($info['type']);
            }

            if ($info['type'] == 'select') {
                $fields[$key]['options'] = $this->fillOptionsForSelectType($info);
            }
        }

        return $fields;
    }

    public function render()
    {
        $this->items = $this->model::all();
        return view('livewire.crud.main');
    }

    public function resetInput()
    {
        array_splice($this->data, 0);
    }

    public function store()
    {
        $this->validate();

        $values = collect($this->data)->toArray();
        unset($values['id']);

        $this->model::create($this->data);

        $this->resetInput();
    }

    public function showCreatePanel($value)
    {
        $this->show_create_panel = $value;
    }

    public function hideInlineEdit()
    {
        $this->editing = null;
    }

    public function showInlineEdit($id)
    {
        $this->editing = $id;
    }

    public function cancel()
    {
        $this->resetInput();
        $this->hideInlineEdit();
    }

    public function edit($id)
    {
        $this->data = $this->model::findOrFail($id)->toArray();
        $this->showInlineEdit($id);
    }

    public function update($id = '')
    {
        $this->validate();

        $id = empty($id) ? @$this->data['id'] : $id;

        if (!$id) {
            // TODO: fail?
            return;
        }

        $id = $this->data['id'];
        $item = $this->model::findOrFail($id);
        $item->update($this->data);

        $this->resetInput();
        $this->hideInlineEdit();
    }

    public function destroy($id)
    {
        if (!$id) {
            return;
        }

        $item = $this->model::findOrFail($id);
        $item->delete();        
    }
}
