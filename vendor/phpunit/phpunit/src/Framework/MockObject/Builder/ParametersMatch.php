mns to use for progress output
  --columns max               Use maximum number of columns for progress output
  --stderr                    Write to STDERR instead of STDOUT
  --stop-on-defect            Stop execution upon first not-passed test
  --stop-on-error             Stop execution upon first error
  --stop-on-failure           Stop execution upon first error or failure
  --stop-on-warning           Stop execution upon first warning
  --stop-on-risky             Stop execution upon first risky test
  --stop-on-skipped           Stop execution upon first skipped test
  --stop-on-incomplete        Stop execution upon first incomplete test
  --fail-on-warning           Treat tests with warnings as failures
  --fail-on-risky             Treat risky tests as failures
  -v|--verbose                Output more verbose information
  --debug                     Display debugging information

  --loader <loader>           TestSuiteLoader implementation to use
  --repeat <times>            Runs the test(s) repeatedly
  --teamcity                  Report test execution progress in TeamCity format
  --testdox                   Report test execution progress in TestDox format
  --testdox-group             Only include tests from the specified group(s)
  --testdox-exclude-group     Exclude tests from the specified group(s)
  --printer <printer>         TestListener implementation to use

  --resolve-dependencies      Resolve d