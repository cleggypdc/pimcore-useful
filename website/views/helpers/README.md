# Helpers
View helpers that can be called

#ScriptQueue
For when you need to include a javascript file on only specific pages, but that script requires jquery or another script in the layout.
The premise behind this is that you should always include JS at the bottom of your HTML.  This helper allows you to include dependancies so that scripts 
can be added anywhere in the view and then output in the correct order.  So you include jquery in a Pimcore Layout and then include a plugin somewhere in the view.

##example

Most use cases would include jquery in the pimcore layout. Add it like this...
```php
<?php $this->scriptQueue()->add('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'); ?>
```

On your homepage you might want to include a jquery plugin for a scroller or gallery etc. The helper allows you to add this
dependancy in the view file...

```php
<?php $this->scriptQueue()->add('scroller', '/website/static/js/scroller.min.js', array('jquery')); ?>
```

The script queue has added the scroller as being dependant on jquery, therefore it will be included AFTER jquery when we print out our script queue.

To print out the script queue, at the bottom of the pimcore layout do the following

```php
<?= $this->scriptQueue->getHtml(); ?>
```