<%-- Bank Deposit table for Order/Invoice on Account page --%>
<table id="BankDepositTable" class="infotable">
	<thead>
		<tr class="gap mainHeader">
			<th>
				<% _t("BankDepositHeading","Bank Deposit") %>
			</th>
		</tr>
	</thead>
	</tbody>
		<tr>
			<td>
				<% with SiteConfig %>
					<% if $BankAccountNumber %>
						<p>
							<% _t("BankAccountNumberTitle", "Bank Account Number: ") %>	
							$BankAccountNumber
						</p>
					<% end_if %>
					<% if $BankAccountDetails %>
						<p>
							$BankAccountDetails
						</p>
					<% end_if %>
					<% if $BankAccountInvoiceMessage %>
						<p>
							$BankAccountInvoiceMessage
						</p>
					<% end_if %>
				<% end_with %>
			</td>
		</tr>
	</tbody>
</table>