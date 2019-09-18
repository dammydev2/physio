any())
            ->method('supports')
            ->will($this->returnValue(true));
        $loader
            ->expects($this->any())
            ->method('load')
            ->will($this->returnValue($importedCollection));
        // import this from the /admin route builder
        $adminRoutes->import('admin.yml', '/imported');

        $collection = $routes->build();
        $this->assertEquals('/admin/dashboard', $collection->get('admin_dashboard')->getPath(), 'Routes before mounting have the prefix');
        $this->assertEquals('/admin/users', $collection->get('admin_users')->getPath(), 'Routes after mounting have the prefix');
        $this->assertEquals('/admin/blog/new', $collection->get('admin_blog_new')->getPath(), 'Sub-collections receive prefix even if mounted before parent prefix');
        $this->assertEquals('/admin/stats/sales', $collection->get('admin_stats_sales')->getPath(), 'Sub-collections receive prefix if mounted after parent prefix');
        $this->assertEquals('/admin/imported/foo', $collection->get('imported_route')-