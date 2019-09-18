{
    "commands": [
        {
            "name": "help",
            "hidden": false,
            "usage": [
                "help [--format FORMAT] [--raw] [--] [<command_name>]"
            ],
            "description": "Displays help for a command",
            "help": "The <info>help<\/info> command displays help for a given command:\n\n  <info>php app\/console help list<\/info>\n\nYou can also output the help in other formats by using the <comment>--format<\/comment> option:\n\n  <info>php app\/console help --format=xml list<\/info>\n\nTo display the list of available commands, please use the <info>list<\/info> command.",
            "definition": {
                "arguments": {
                    "command_name": {
                        "name": "command_name",
                        "is_required": false,
                        "is_array": false,
                        "description": "The command name",
                        "default": "help"
                    }
                },
                "options": {
                    "format": {
                        "name": "--format",
                        "shortcut": "",
                        "accept_value": true,
                        "is_value_required": true,
                        "is_multiple": false,
                        "description": "The output format (txt, xml, json, or md)",
                        "default": "txt"
                    },
                    "raw": {
                        "name": "--raw",
                        "shortcut": "",
                        "