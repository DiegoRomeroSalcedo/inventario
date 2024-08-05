let table = new DataTable('#example');
        new DataTable.Buttons(table, {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
        
        table
            .buttons(0, null)
            .container()
            .prependTo(table.table().container());