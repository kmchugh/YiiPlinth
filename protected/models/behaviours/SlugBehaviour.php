<?php
class SlugBehaviour
    extends CActiveRecordBehavior
{
    public $slug = null;
    public $unique = true;
    public $slugColumn = 'Slug';
    public $update = true;


    /**
     * Creates and updates the slug values for SEO friendly URLs
     * @param CModelEvent $toEvent the event for the behaviour
     */
    public function beforeValidate($toEvent)
    {
        $this->getOwner()->{$this->slugColumn} = $this->generateSlug();
    }

    /**
     * Generates a slug string from a list of slug values
     * @return string the slug value
     */
    public function generateSlug()
    {
        // Only update if the slug does not exist, or if we have been asked to update
        if ((is_callable($this->slug) || is_array($this->slug)) && true === $this->update || empty($this->getOwner()->{$this->slugColumn}))
        {
            // The slug is a function that can be called to return the slug value
            if (is_callable($this->slug))
            {
                // Generate the slug values
                $laValues = call_user_func($this->slug, $this->getOwner());
            }
            else
            {
                $laValues = array();
                // The slug is a list of columns
                foreach ($this->slug as $loColumn)
                {
                    $laValues[] = $this->getOwner()->{$loColumn};
                }
            }
            return $this->slugFromValue(is_array($laValues) ? implode('-', $laValues) : $laValues);
        }
        return '';
    }

    public function slugFromValue($tcValue)
    {
        return trim(preg_replace('@[\s!<>,^{}:;|`_\?=\\\+\*/%&#]+@', '-', strtolower($tcValue)));
    }
}


?>