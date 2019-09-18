 = substr($message, $userOffset, $userLength);
            $data[$i++] = substr($message, $workOffset, $workLength);
            $data[$i++] = substr($message, $lmOffset, $lmLength);
            $data[$i] = substr($message, $ntmlOffset, $ntmlLength);

            $map = [
                'LM Response Security Buffer',
                'NTLM Response Security Buffer',
                'Target Name Security Buffer',
                'User Name Security Buffer',
                'Workstation Name Security Buffer',
                'Session Key Security Buffer',
                'Flags',
                'Target Name Data',
                'User Name Data',
                'Workstation Name Data',
                'LM Re