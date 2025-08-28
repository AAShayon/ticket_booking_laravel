'use client';

import { useState } from 'react';
import { useForm, useFieldArray } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { PassengerDetailsSchema } from '@/lib/validations/booking';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';

const PassengerFormSchema = z.object({
  passengers: z.array(PassengerDetailsSchema).min(1),
});

type PassengerFormValues = z.infer<typeof PassengerFormSchema>;

interface PassengerFormProps {
  numberOfSeats: number;
  onSubmit: (data: PassengerFormValues) => void;
  defaultValues?: PassengerFormValues;
}

export function PassengerForm({ numberOfSeats, onSubmit, defaultValues }: PassengerFormProps) {
  const form = useForm<PassengerFormValues>({
    resolver: zodResolver(PassengerFormSchema),
    defaultValues: defaultValues || {
      passengers: Array.from({ length: numberOfSeats }, () => ({ name: '', phone: '', email: '' })),
    },
  });

  const { fields } = useFieldArray({
    name: 'passengers',
    control: form.control,
  });

  const handleSubmit = (data: PassengerFormValues) => {
    onSubmit(data);
  };

  return (
    <Card className="w-full max-w-2xl">
      <CardHeader>
        <CardTitle>Passenger Details</CardTitle>
        <p className="text-sm text-muted-foreground">
          Please provide details for all passengers
        </p>
      </CardHeader>
      <CardContent>
        <Form {...form}>
          <form onSubmit={form.handleSubmit(handleSubmit)} className="space-y-6">
            {fields.map((field, index) => (
              <div key={field.id} className="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg">
                <div className="md:col-span-3">
                  <h3 className="text-lg font-medium">Passenger {index + 1}</h3>
                </div>
                <FormField
                  control={form.control}
                  name={`passengers.${index}.name`}
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>Name *</FormLabel>
                      <FormControl>
                        <Input placeholder="Full name" {...field} />
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                <FormField
                  control={form.control}
                  name={`passengers.${index}.phone`}
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>Phone *</FormLabel>
                      <FormControl>
                        <Input placeholder="Phone number" {...field} />
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                <FormField
                  control={form.control}
                  name={`passengers.${index}.email`}
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>Email</FormLabel>
                      <FormControl>
                        <Input placeholder="Email (optional)" {...field} />
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />
              </div>
            ))}
            <div className="flex justify-end">
              <Button type="submit">Continue to Summary</Button>
            </div>
          </form>
        </Form>
      </CardContent>
    </Card>
  );
}