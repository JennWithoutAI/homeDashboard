# Little info dump about the system

Folders are for the following :

JsonSheets = Data and acts as DB (mostly easy for testing without having to deal with SQL)

Modules = Seperate Items that can be loaded or unloaded based on the jsonFile in JsonSheets/system/onloader.json

System = The core of the project, the loading and the start. aka the nasty you dont want to see every time you open a project.
         But make sure that you do make a onloader module in system.


Extra's : 

Why do i want a ModuleLoader folder in every module?
Because.. i overcomplicated it but it looks smoother and you always know thats the first point of contact with the system