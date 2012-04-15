<?php

if($error !== null)
{
    echo '<p class="error">' . form_prep($error) . '</p>';
}

$n = '<br/>';

echo form_open('adminpanel', array('id' => 'adminform'))

  .$n.  form_label('Similarity measure:', 'similarityMeasure')
  .$n.  form_dropdown('similarityMeasure', array(
                                            "Dice's",
                                            "Jaccard's",
                                            "Cosine",
                                            "Overlap"
                                          ), $similarityMeasure)

  .$n.  form_label('X-factor (0-1):', 'xFactor')
  .$n.  form_input('xFactor', $xFactor, array('type' => 'range', 'min'=> '0', 'max' => '1'))

  .$n.  form_label('Alpha factor (0-1):', 'alpha')
  .$n.  form_input('alpha', $alpha, array('type' => 'range', 'min'=> '0', 'max' => '1'))

  .$n.  form_submit('submit', 'Apply')
  .$n.  form_close()
  ;
