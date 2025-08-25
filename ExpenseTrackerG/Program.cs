using System;

namespace ExpenseTracker
{
    class Program
    {
        static void Main(string[] args)
        {
            decimal EssentialExpenses = 0;
            decimal FunExpenses = 0;
            bool run = true;

            while (run)
            {
                Console.WriteLine("input the number for the operation you want: \n1) Essential Expense Tracker \n2) Fun Expense Tracker \n3) Total Expenses \n4) Exit");
                int choice = Convert.ToInt32(Console.ReadLine());
                {
                    switch (choice)
                    {
                        case 1:
                            EssentialExpenses = Track.EssentialExpense();
                            Console.WriteLine("The total of the essential expenses is: " + EssentialExpenses + "$\n");
                            break;

                        case 2:
                            FunExpenses = Track.FunExpense();
                            Console.WriteLine("The total of the fun expenses is: " + FunExpenses + "$\n");
                            break;

                        case 3:
                            decimal TotalExpenses = EssentialExpenses + FunExpenses;
                            Console.WriteLine("\nThe total you spent this month is: " + TotalExpenses + "$\n");
                            break;

                        case 4:
                            run = false;
                            break;

                        default:
                            Console.WriteLine("Invalid option. Please try again.");
                            break;
                    }
                }
            }

        }
        class Track
        {
            public static decimal FunExpense()
            {
                List<decimal> fun = new List<decimal>();

                //need to make an funexpense function and then call it in the main method which needs to be in another class which we are gonna name Program
                //also the function needs to be able to terminate if the user finished inputting the expenses

                while (true)
                {
                    Console.WriteLine("\nEnter your fun expenses type \"finish\" when you're done: ");
                    string input = Console.ReadLine();
                    if (input == "finish")
                    {
                        decimal total = 0;

                        foreach (decimal expense in fun)
                        {
                            total += expense;
                        }

                        return total;
                    }
                    else
                    {
                        try
                        {
                            fun.Add(Convert.ToDecimal(input));
                        }
                        catch
                        {
                            Console.WriteLine("Invalid input, please enter a number or \"finish\" to end!");
                        }
                    }
                }
            }

            public static decimal EssentialExpense()
            {
                List<decimal> ess = new List<decimal>();

                while (true)
                {
                    Console.WriteLine("\nEnter your Essential expenses type \"finish\" when you're done: ");
                    string input = Console.ReadLine();
                    if (input == "finish")
                    {
                        decimal total = 0;

                        foreach (decimal expense in ess)
                        {
                            total += expense;
                        }

                        return total;
                    }
                    else
                    {
                        try
                        {
                            ess.Add(Convert.ToDecimal(input));
                        }
                        catch
                        {
                            Console.WriteLine("Invalid input, please enter a number or \"finish\" to end!");
                        }
                    }
                }
            }
        }
    }
}
