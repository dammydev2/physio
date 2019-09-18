,
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Disable ANSI output",
                        "default": false
                    },
                    "no-interaction": {
                        "name": "--no-interaction",
                        "shortcut": "-n",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Do not ask any interactive question",
                        "default": false
                    }
                }
            }
        },
        {
            "name": "list",
            "hidden": false,
            "usage": [
                "list [--raw] [--format FORMAT] [--] [<namespace>]"
            ],
            "description": "Lists commands",
            "help": "The <info>list<\/info> command lists all commands:\n\n  <info>php app\/console list<\/info>\n\nYou can also display the commands for a specific namespace:\n\n  <info>php app\/console list test<\/info>\n\nYou can also output the information in other formats by using the <comment>--format<\/comment> option:\n\n  <info>php app\/console list --format=xml<\/info>\n\nIt's also possible to get raw list of commands (useful for embedding command runner):\n\n  <info>php app\/console list --raw<\/info>",
            "definition": {
                "arguments": {
                    "namespace": {
                        "name": "namespace",
                        "is_required": false,
                        "is_array": false,
                        "description": "The namespace name",
                        "default": null
                    }
                },
                "options": {
                    "raw": {
                        "name": "--raw",
                        "shortcut": "",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "To output raw command list",
                        "default": false
                    },
                    "format": {
                        "name": "--format",
                        "shortcut": "",
                        "accept_value": true,
                        "is_value_required": true,
                        "is_multiple": false,
                        "description": "The output format (txt, xml, json, or md)",
                        "default": "txt"
                    }
                }
            }
        },
        {
            "name": "descriptor:command1",
            "hidden": false,
            "usage": [
                "descriptor:command1",
                "alias1",
                "alias2"
            ],
            "description": "command 1 description",
            "help": "command 1 help",
            "definition": {
                "arguments": [],
                "options": {
                    "help": {
                     