# magento2-delete-order-comments

A Magento 2 custom module adding additional functionality to view a list order history comments via an increment id. By using the entity_id of the desired comment entry an additional symfony command can then be used to delete this comment should the content have been deemed inappropriate or an error have occurred during entry.

from the magento root on the command line:

<code>bin/magento benjaminbrant:ordercomment:findordercomments <increment id of order></code>

An array will be output to the screen made up of each comment from the order:

<pre>
...
Array
(
[entity_id] => 56129924
[parent_id] => 5136619
[is_customer_notified] => 0
[is_visible_on_front] => 0
[comment] => Test comment on the order
[status] => pending_cheque
[created_at] => 2023-03-01 14:18:25
[entity_name] => order
[store_id] => 5
)
...
</pre>

Once the offending comment has been identified then by use of the entity_id it can be deleted by using the following command:

<code>bin/magento benjaminbrant:ordercomment:deleteordercomment <entity_id></code>