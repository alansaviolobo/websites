/**
	*Follow the instruction for installing and configuring nice-menu module
	**/
	
1. Download module nice-menus from http://ftp.drupal.org/files/projects/nice_menus-6.x-1.3.tar.gz

2. Unzip and copy the directory /nice_menus from the download to /sites/all/modules/ directory of your drupal installation

3. Goto Administer->Site Building->Modules and enable the "Nice Menus" module in the Other category.

4. Goto Administer->Site Building->Blocks and configure the Nice Menu with following settings:
		
		Block Specific settings:
		
		a)Block title: <none>
		
		b)Menu Name: Nice Menu 1(by default)
		
		c)Source Menu tree: <Primary links>
		
		d)Menu style: down
		
		Save settings
		
5. Assign block Nice Menu to the region main_menu and save the configuration.

6. Goto Administer->Site Building->Themes->Configure. 
				
					Set ,
					 
					 Path to custom Nice Menus CSS file :  sites/all/themes/custom/enblic/nice_menu.css
					 
	Save Configuration.
