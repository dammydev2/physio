quired": false,
                        "is_multiple": false,
                        "description": "Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug",
                        "default": false
                    },
                    "version": {
                        "name": "--version",
                        "shortcut": "-V",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this application version",
                        "default": false
                    },
                    "ansi": {
                        "name": "--ansi",
                        "shortcut": "",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Force ANSI output",
                        "default": false
                    },
                    "no-ansi": {
                        "name": "--no-ansi",
                        "shortcut": "",
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
            "name": "descriptor:command2",
            "hidden": false,
            "usage": [
                "descriptor:command2 [-o|--option_name] [--] <argument_name>",
                "descriptor:command2 -o|--option_name <argument_name>",
                "descriptor:command2 <argument_name>"
            ],
            "description": "command 2 description",
            "help": "command 2 help",
            "definition": {
                "arguments": {
                    "argument_name": {
                        "name": "argument_name",
                        "is_required": true,
                        "is_array": false,
                        "description": "",
                        "default": null
                    }
                },
                "options": {
                    "option_name": {
                        "name": "--option_name",
                        "shortcut": "-o",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "",
                        "default": false
                    },
                    "help": {
                        "name": "--help",
                        "shortcut": "-h",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this help message",
                        "default": false
                    },
                    "quiet": {
                        "name": "--quiet",
                        "shortcut": "-q",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Do not output any message",
                        "default": false
                    },
                    "verbose": {
                        "name": "--verbose",
                        "shortcut": "-v|-vv|-vvv",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug",
                        "default": false
                    },
                    "version": {
                        "name": "--version",
                        "shortcut": "-V",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this application version",
                        "default": false
                    },
                    "ansi": {
                        "name": "--ansi",
                        "shortcut": "",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Force ANSI output",
                        "default": false
                    },
                    "no-ansi": {
                        "name": "--no-ansi",
                        "shortcut": "",
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
            "name": "descriptor:command3",
            "hidden": true,
            "usage": [
                "descriptor:command3"
            ],
            "description": "command 3 description",
            "help": "command 3 help",
            "definition": {
                "arguments": {},
                "options": {
                    "help": {
                        "name": "--help",
                        "shortcut": "-h",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this help message",
                        "default": false
                    },
                    "quiet": {
                        "name": "--quiet",
                        "shortcut": "-q",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Do not output any message",
                        "default": false
                    },
                    "verbose": {
                        "name": "--verbose",
                        "shortcut": "-v|-vv|-vvv",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug",
                        "default": false
                    },
                    "version": {
                        "name": "--version",
                        "shortcut": "-V",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this application version",
                        "default": false
                    },
                    "ansi": {
                        "name": "--ansi",
                        "shortcut": "",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Force ANSI output",
                        "default": false
                    },
                    "no-ansi": {
                        "name": "--no-ansi",
                        "shortcut": "",
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
            "name": "descriptor:command4",
            "hidden": false,
            "usage": [
                "descriptor:command4",
                "descriptor:alias_command4",
                "command4:descriptor"
            ],
            "description": null,
            "help": "",
            "definition": {
                "arguments": {},
                "options": {
                    "help": {
                        "name": "--help",
                        "shortcut": "-h",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this help message",
                        "default": false
                    },
                    "quiet": {
                        "name": "--quiet",
                        "shortcut": "-q",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Do not output any message",
                        "default": false
                    },
                    "verbose": {
                        "name": "--verbose",
                        "shortcut": "-v|-vv|-vvv",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug",
                        "default": false
                    },
                    "version": {
                        "name": "--version",
                        "shortcut": "-V",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Display this application version",
                        "default": false
                    },
                    "ansi": {
                        "name": "--ansi",
                        "shortcut": "",
                        "accept_value": false,
                        "is_value_required": false,
                        "is_multiple": false,
                        "description": "Force ANSI output",
                        "default": false
                    },
                    "no-ansi": {
                        "name": "--no-ansi",
                        "shortcut": "",
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
                        "is_value_required": 