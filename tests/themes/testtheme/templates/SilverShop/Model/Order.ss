<!--   this is the test theme -->
<% include SilverShop\Model\Order_Address %>
<% include SilverShop\Model\Order_Content %>
<% if Total %>
    <% if $Payments %>
        <% include SilverShop\Model\Order_Payments %>
    <% end_if %>

    <table id="OutstandingTable" class="infotable">
        <tbody>
            <tr class="gap summary" id="Outstanding">
                <th colspan="4" scope="row" class="threeColHeader"><strong><%t SilverShop\Model\Order.TotalOutstanding "Total outstanding" %></strong></th>
                <td class="right">
                    <strong>

                        <%-- Silvershop Bank Deposit --%>
						<% if $Status == "Unpaid" %>
							$TotalOutstanding.Nice
						<% else %>
							<% if $Status == "MemberCancelled" || $Status == "AdminCancelled" %>
								<%t SilverShop\\Model\\Order.STATUS_MEMBERCANCELLED "Order Cancelled" %>
							<% else %>
								<%t SilverShop\\Model\\Order.STATUS_PAID "Paid" %>
							<% end_if %>
						<% end_if %>
                        <%-- end Silvershop Bank Deposit --%>

                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
<% end_if %>

<% if $Notes %>
    <table id="NotesTable" class="infotable">
        <thead>
            <tr>
                <th><%t SilverShop\Model\Order.db_Notes "Notes" %></th>
            </tr>
        </thead>
        </tbody>
            <tr>
                <td>$Notes</td>
            </tr>
        </tbody>
    </table>
<% end_if %>

<%-- Silvershop Bank Deposit --%>
<% if Total %>
	<% if TotalOutstanding %>
		<% if Payments %>
			<% loop Payments.last %>
				<% if GatewayTitle == "Bank Deposit" %>
					<% include SilverShop\Model\Order_BankDeposit %>
				<% end_if %>
			<% end_loop %>
		<% end_if %>
	<% end_if %>
<% end_if %>
<%-- end Silvershop Bank Deposit --%>
