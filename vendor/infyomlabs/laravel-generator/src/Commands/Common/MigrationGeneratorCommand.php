= explode(',', $field['htmlTypeInputs']);
                    $radioButtons = [];
                    foreach ($inputsArr as $item) {
                        $radioButtonsTemplate = fill_field_template(
                            $this->commandData->fieldNamesMapping,
                            $radioTemplate, $field
                        );
                        $radioButtonsTemplate = str_replace('$VALUE$', $item, $radioButtonsTemplate);
                        $radioButtons[] = $radioButtonsTemplate;
                    }
                    $fieldTemplate = str_replace('$RADIO_BUTTONS$', implode("\n", $radioButtons), $fieldTemplate);
                    break;

//                case 'checkbox-group':
//                    $fieldTemplate = get_template('vuejs.fields.checkbox_group', $this->templateType);
//                      $radioTemplate = get_template('vuejs.fields.checks', $this->templateType);
//                      $inputsArr = explode(',', $field['htmlTypeInputs']);
//                      $radioButtons = [];
//                      foreach ($inputsArr as $item) {
//                          $radioButtonsTemplate = fill_field_template(
//                              $this->commandData->fieldNamesMapping,
//                              $radioTemplate,
//                              $field
//                          );
//                          $radioButtonsTemplate = str_replace('$VALUE$', $item, $radioButtonsTemplate);
//                          $radioButtons[] = $radioButtonsTemplate;
//                      }
//                   