         }

            if (isset($phpunitConfiguration['reverseDefectList']) && !isset($arguments['reverseList'])) {
                $arguments['reverseList'] = $phpunitConfiguration['reverseDefectList'];
            }

            if (isset($phpunitConfiguration['forceCoversAnnotation']) && !isset($arguments['forceCoversAnnotation'])) {
                $arguments['forceCoversAnnotation'] = $phpunitConfiguration['forceCoversAnnotation'];
            }

            if (isset($phpunitConfiguration['disableCodeCoverageIgnore']) && !isset($arguments['disableCodeCoverageIgnore'])) {
                $arguments['disableCodeCoverageIgnore'] = $phpunitConfiguration['disableCodeCoverageIgnore'];
            }

            if (isset($phpunitConfiguration['registerMockObjectsFromTestArgumentsRecursively']) && !isset($arguments['registerMockObjectsFromTestArgumentsRecursively'])) {
                $arguments['registerMockObjectsFromTestArgumentsRecursively'] = $phpunitConfiguration['registerMockObjectsFromTestArgumentsRecursively'];
            }

            if (isset($phpunitConfiguration['executionOrder']) && !isset($arguments['executionOrder'])) {
                $arguments['executionOrder'] = $phpunitConfiguration['executionOrder'];
            }

            if (isset($phpunitConfiguration['executionOrderDefects']) && !isset($arguments['executionOrderDefects'])) {
                $arguments['executionOrderDef