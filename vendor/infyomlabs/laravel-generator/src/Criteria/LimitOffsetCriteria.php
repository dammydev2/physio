return this.$http({url: url, method: method, data: data});
        },            
        cleanData: function() {
            this.row = objectRow;
            this.flashMessage = '';
            this.flashType = '';
        },            
        success: function(response) {
            if (response.data.data) {
                var data = response.data.data;
                vm.$set('row', data);
            }
            if (this.method == 'POST' || this.method == 'PATCH' || this.method == 'DELETE')
                this.$broadcast('vuetable:reload');
            var message = response.data.message;
            vm.flashMessage = message;
            vm.flashType = 'success';
        },
        failed: function(response) {
            vm.flashMessage = vm.defaultErrorMessage;
            vm.flashType = vm.flashTypeDanger;
            if (response.data.errors) {
                vm.updateErrors(response.data.errors);
            }
        },
     