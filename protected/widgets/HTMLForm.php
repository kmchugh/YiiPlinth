<?php
class HTMLForm extends CActiveForm
{
    // The layout used to surround the <form> element
    public $containerLayout = '//layouts/_form';

    public $fieldLayout = '//layouts/_field';

    // The class attached to the form container
    public $containerClass = '';

    // The title of the form
    public $title = '';

    // The list of fields to be rendered
    public $fields = array();

    // The list of links to render
    public $links = array();

    // The model being rendered
    public $model = NULL;

    public $buttons = array();

    // Should an oauth marker be rendered?
    public $oauth = false;


    /**
     * Initializes the widget.
     * Starts building up the form widget
     */
    public function init()
    {
        if(!isset($this->htmlOptions['id']))
        {
            $this->htmlOptions['id']=$this->id;
        }

        // If there is a file upload, change the enc type
        foreach($this->fields as $loField)
        {
            if (trim($loField['type'])==='imageupload')
            {
                $this->htmlOptions['enctype'] = 'multipart/form-data';
                break;
            }
        }
    }

    /**
     * Checks if we should not use the model for rendering this field
     * @param $toField the field to check
     * @return bool true if we don't have a model or the field has explicitly asked to skip the model
     */
    private function skipModel($toField)
    {
        return is_null($this->model) || (isset($toField['skipModel']) && $toField['skipModel'] === true);
    }

    /**
     * Renders a field
     */
    public function field($toField)
    {
        static $lnCount = 0;

        // TODO: This should not be a hard coded reset, add a defaultLayout instead
        $this->fieldLayout = '//layouts/_field';

        $llSkipModel = $this->skipModel($toField);
        $lcAttribute = $llSkipModel ? NULL : $toField['label'];
        $lcID = isset($toField['id']) ? $toField['id'] : $this->id.'_'.$lnCount++;
        $lcName = isset($toField['name']) ? $toField['name'] : NULL;
        $lcValue = $llSkipModel ? isset($toField['value']) ? $toField['value'] : NULL : $this->model[$lcAttribute];
        $lcPlaceholder = isset($toField['placeholder']) ? $toField['placeholder'] : '';

        $llNoLabel = (isset($toField['noLabel']) && $toField['noLabel'] === true);

        $laValues = isset($toField['class']) ? array('tcClass'=>$toField['class']) : array();
        $laValues['tcFieldContent'] = '';
        $laValues['tcError'] = $llSkipModel || !property_exists($this->model, $lcAttribute) ? (isset($toField['error']) ? $toField['error'] : '') : $this->error($this->model, $lcAttribute);
        if (isset($toField['hint']))
        {
            $laValues['tcFieldHint'] = $toField['hint'];
        }

        $laOptions = $llSkipModel ?
                array(
                    'id'=>$lcID,
                    'name'=>$lcName,
                ) : array();


        if ($lcValue != null)
        {
            $laOptions['value'] = $lcValue;
        }
        $laValues['toValue'] = $lcValue;

        if (count($lcPlaceholder) > 0)
        {
            $laOptions['placeholder'] = $lcPlaceholder;
        }

        // Create the field label based on if we are using the model or not
        if (!$llNoLabel)
        {
            $laValues['tcFieldLabel'] = $llSkipModel ?
                PlinthHTML::label($toField['label'], $lcID):
                $this->labelEx($this->model, $lcAttribute) ;
        }
        else
        {
            $laValues['tcFieldLabel'] = '';
        }

        // Create the actual input
        switch (strtolower($toField['type']))
        {
            case 'radio' :
                $lnCount = 0;
                foreach ($toField['options'] as $lcCurrentValue=>$lcOption)
                {
                    $lcOptionID = $lcID.'_'.$lnCount;
                    $laValues['tcFieldContent'].= '<span class=\'radioItem\'>'. ($llSkipModel ?
                        PlinthHTML::radioButton($lcName, $lcValue === $lcCurrentValue, array('id'=>$lcOptionID)) :
                        $this->radioButton($this->model, $lcAttribute, array('id'=>$lcOptionID))).PlinthHTML::label($lcOption, $lcOptionID).'</span>';
                    $lnCount++;
                }
                break;

            case 'checkbox' :
                // Render the Checkbox
                $laValues['tcFieldLabel'] = '';
                $laValues['tcFieldContent'].= '<span class=\'checkItem\'>'. ($llSkipModel ?
                        PlinthHTML::checkBox($lcName, $lcValue === 1, $laOptions) :
                        $this->checkBox($this->model, $lcAttribute, $laOptions));

                // Render the label
                $laValues['tcFieldContent'].= $llSkipModel ?
                    PlinthHTML::label($toField['label'], $lcID):
                    $this->labelEx($this->model, $lcAttribute).'</span>';
                break;

            case 'select' :
                $laValues['tcFieldContent'].= $llSkipModel ?
                    PlinthHTML::dropDownList($lcName, $lcValue, $toField['options'], $laOptions) :
                    $this->dropDownList($this->model, $lcAttribute, $toField['options'], $laOptions);
                break;

            case 'keygen' :
                $laValues['tcFieldContent'].= "<keygen name=\"$lcName\" id=\"$lcID\"/>";
                break;

            case 'textarea' :
                $laValues['tcFieldContent'].= $llSkipModel ?
                    PlinthHTML::textArea($lcName, $lcValue, $laOptions) :
                    $this->textArea($this->model, $lcAttribute, $laOptions);
                break;

            case 'label' :
                $laValues['tcFieldContent'].= '<label class="input">'.($llSkipModel ? $lcValue : $this->model[$lcAttribute]).'</label>';
                break;

            case 'link' :
                $laValues['tcFieldLabel'] = '';
                $laValues['tcFieldContent'].= CHtml::link($toField['label'],array($toField['url']));
                $laValues['tcError'] = '';
                break;

            case 'captcha' :
                $this->fieldLayout = '//layouts/_captcha';
                $laValues['tcFieldContent'].= $llSkipModel ?
                    PlinthHTML::textField($lcName, $lcValue, $laOptions) :
                    $this->textField($this->model, $lcAttribute, $laOptions);
                break;

            case 'imageupload' :
                $laOptions['dromos-module']='fileupload/dromos.fileupload';
                $this->fieldLayout = '//layouts/_imageUpload';
                $laValues['tcFieldContent'].= $llSkipModel ?
                    PlinthHTML::fileField($lcName, $lcValue, $laOptions) :
                    $this->fileField($this->model, $lcAttribute, $laOptions);
                break;

            case 'list' :
                $lcListID = $lcID.'_dataList';
                $lcList = "<datalist id=\"$lcListID\">";
                foreach ($toField['options'] as $lcCurrentValue=>$lcOption)
                {
                    $lcList.="<option value=\"$lcCurrentValue\">$lcOption</option>";
                }
                $lcList.='</datalist>';
                $laValues['tcFieldContent'].= $lcList."<input list=\"$lcListID\" name=\"$lcName\" placeholder=\"$lcPlaceholder\" id=\"$lcID\"/>";
                break;
            default :
                $laOptions['type']=$toField['type'];
                $laValues['tcFieldContent'].= $llSkipModel ?
                    PlinthHTML::textField($lcName, $lcValue, $laOptions) :
                    PlinthHTML::activeTextField($this->model, $lcAttribute, $laOptions);
        }

        return $this->renderFile($this->controller->getLayoutFile($this->fieldLayout),$laValues, true);
    }

    /**
     * Renders a button
     */
    public function button($toButton)
    {
        return PlinthHTML::htmlButton($toButton['title'],
            isset($toButton['type']) ? $toButton['type'] : 'button');
    }

    /**
     * Renders a link
     */
    public function link($toLink)
    {
        return PlinthHTML::link($toLink['title'], $toLink['url']);
    }

    /**
     * Runs the widget.
     * This registers the necessary javascript code and renders the final form
     */
    public function run()
    {
        // Set the form focus if needed
        if(is_array($this->focus))
        {
            $this->focus="#".CHtml::activeId($this->focus[0],$this->focus[1]);
        }

        // Render the file output
        $this->renderFile($this->controller->getLayoutFile($this->containerLayout), array(), false);

        $cs=Yii::app()->clientScript;
        if(!$this->enableAjaxValidation && !$this->enableClientValidation || empty($this->attributes))
        {
            if($this->focus!==null)
            {
                $cs->registerCoreScript('jquery');
                $cs->registerScript('CActiveForm#focus',"
					if(!window.location.hash)
						$('".$this->focus."').focus();
				");
            }
            return;
        }

        $options=$this->clientOptions;
        if(isset($this->clientOptions['validationUrl']) && is_array($this->clientOptions['validationUrl']))
            $options['validationUrl']=CHtml::normalizeUrl($this->clientOptions['validationUrl']);

        $options['attributes']=array_values($this->attributes);

        if($this->summaryID!==null)
            $options['summaryID']=$this->summaryID;

        if($this->focus!==null)
            $options['focus']=$this->focus;

        $options=CJavaScript::encode($options);
        $cs->registerCoreScript('yiiactiveform');
        $id=$this->id;
        $cs->registerScript(__CLASS__.'#'.$id,"\$('#$id').yiiactiveform($options);");
    }
}
?>