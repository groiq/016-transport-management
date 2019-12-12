
# Azure portal:

https://azure.microsoft.com/de-de/features/azure-portal/

# Generated Values in SQL

http://www.mysqltutorial.org/mysql-generated-columns/

# XAMP troubleshooting

https://stackoverflow.com/questions/57128891/how-repair-corrupt-xampp-mysql-user-table

option file locations:
https://mariadb.com/kb/en/library/configuring-mariadb-with-option-files/

user account control issue:

https://www.wikitechy.com/fix-error/how-to-fix-uac-please-avoid-to-install-xampp

very clean guide to setting php executable in vscode

https://webstoked.com/fix-cannot-validate-php-vs-code/

# var

## timestamp to default null

https://stackoverflow.com/questions/20520443/mysql-timestamp-to-default-null-not-current-timestamp

# Date / Time picker

https://github.com/uxsolutions/bootstrap-datepicker

# Editable List

https://memorynotfound.com/dynamically-addremove-items-list-javascript/

<ul id="dynamic-list"></ul>

<input type="text" id="candidate"/>
<button onclick="addItem()">add item</button>
<button onclick="removeItem()">remove item</button>

function addItem(){
	var ul = document.getElementById("dynamic-list");
  var candidate = document.getElementById("candidate");
  var li = document.createElement("li");
  li.setAttribute('id',candidate.value);
  li.appendChild(document.createTextNode(candidate.value));
  ul.appendChild(li);
}

function removeItem(){
	var ul = document.getElementById("dynamic-list");
  var candidate = document.getElementById("candidate");
  var item = document.getElementById(candidate.value);
  ul.removeChild(item);
}

https://mdbootstrap.com/docs/jquery/tables/editable/

editable table!


 $tableID.on('click', '.table-down', function () {

   const $row = $(this).parents('tr');
   $row.next().after($row.get(0));
 });

Maybe start with the first example and then integrate the move function from the second?

...or just save sortability for the next milestone

https://codepen.io/santushDN/full/WWeJeQ

http://www.expertphp.in/article/add-remove-input-fields-dynamically-using-jquery

https://bootsnipp.com/snippets/vrA

https://bootsnipp.com/snippets/rg72r

https://bootsnipp.com/snippets/kM0M

https://bootsnipp.com/snippets/XaXB0

https://www.viralpatel.net/dynamic-add-textbox-input-button-radio-element-html-javascript/
